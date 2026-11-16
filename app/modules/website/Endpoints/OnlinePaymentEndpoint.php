<?php

namespace App\Website\Endpoints;

use App\Core\Request;
use App\Core\Response;
use App\CRM\Repositories\CustomerRepository;
use App\Shop\Repositories\OrderRepository;
use App\Website\Services\VietQrPaymentService;
use DomainException;

class OnlinePaymentEndpoint
{
	public function qr(): void
	{
		$input = Request::json();
		$orderId = filter_var($input['order_id'] ?? null, FILTER_VALIDATE_INT);
		$phone = $input['phone'] ?? null;

		if ($orderId === false || $orderId < 1 || !is_string($phone) || trim($phone) === '') {
			Response::error('Vui lòng cung cấp mã đơn và số điện thoại hợp lệ.', 422);
		}

		$orderData = (new OrderRepository())->show($orderId);
		$order = $orderData[0][0] ?? null;

		if (!$order) {
			Response::error('Không tìm thấy đơn hàng hoặc thông tin không khớp.', 404);
		}

		$customer = (new CustomerRepository())->findById((int) ($order['customer_id'] ?? 0));
		$storedPhone = $this->normalizePhone((string) ($customer['phone'] ?? ''));
		$submittedPhone = $this->normalizePhone($phone);

		if (!$customer || $storedPhone === '' || !hash_equals($storedPhone, $submittedPhone)) {
			Response::error('Không tìm thấy đơn hàng hoặc thông tin không khớp.', 404);
		}

		$paymentStatus = (string) ($order['payment'] ?? '');
		$orderStatus = (string) ($order['status'] ?? '');
		$amountDue = (float) ($order['debt_amount'] ?? 0);

		if (
			!in_array($paymentStatus, ['unpaid', 'partial'], true) ||
			in_array($orderStatus, ['cancelled', 'canceled'], true) ||
			$amountDue <= 0
		) {
			Response::error('Đơn hàng không còn khoản thanh toán cần xử lý.', 409);
		}

		try {
			$payment = (new VietQrPaymentService())->createQr($orderId, $amountDue);
		} catch (DomainException $error) {
			Response::error($error->getMessage(), 503);
		}

		header('Cache-Control: no-store, private');

		Response::success($payment);
	}

	private function normalizePhone(string $phone): string
	{
		return preg_replace('/[^0-9]/', '', $phone) ?? '';
	}
}
