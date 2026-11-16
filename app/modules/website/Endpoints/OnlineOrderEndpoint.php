<?php

namespace App\Website\Endpoints;

use App\Core\Request;
use App\Core\Response;
use App\CRM\Repositories\CustomerRepository;
use App\CRM\Validators\CustomerValidator;
use App\Shop\Repositories\OrderRepository;
use App\Shop\Repositories\ProductRepository;
use App\Shop\Validators\OrderValidator;

class OnlineOrderEndpoint
{
	public function create(): void
	{
		$input = Request::json();
		$customer = $input['customer'] ?? null;

		if (!is_array($customer)) {
			$this->fail('Thông tin khách hàng không hợp lệ.');
		}

		foreach (['name', 'phone', 'email', 'address'] as $field) {
			if (isset($customer[$field]) && !is_string($customer[$field])) {
				$this->fail('Thông tin khách hàng không hợp lệ.');
			}
		}

		if (isset($input['note']) && !is_string($input['note'])) {
			$this->fail('Ghi chú đơn hàng không hợp lệ.');
		}

		if (isset($input['delivery_method']) && !is_string($input['delivery_method'])) {
			$this->fail('Phương thức nhận hàng không hợp lệ.');
		}

		$name = trim((string) ($customer['name'] ?? ''));
		$phone = trim((string) ($customer['phone'] ?? ''));
		$email = trim((string) ($customer['email'] ?? ''));
		$address = trim((string) ($customer['address'] ?? ''));
		$note = trim((string) ($input['note'] ?? ''));
		$deliveryMethod = (string) ($input['delivery_method'] ?? 'delivery');

		if (
			strlen($name) > 255 ||
			strlen($phone) > 20 ||
			strlen($email) > 255 ||
			strlen($address) > 255 ||
			strlen($note) > 2000
		) {
			$this->fail('Một số thông tin vượt quá độ dài cho phép.');
		}

		if ($phone === '') {
			$this->fail('Vui lòng nhập số điện thoại.');
		}

		if ($deliveryMethod === 'delivery' && $address === '') {
			$this->fail('Vui lòng nhập địa chỉ nhận hàng.');
		}

		if (!in_array($deliveryMethod, ['delivery', 'pickup'], true)) {
			$this->fail('Phương thức nhận hàng không hợp lệ.');
		}

		$customerData = [
			'name' => $name,
			'phone' => $phone,
			'email' => $email !== '' ? $email : null,
			'address' => $address !== '' ? $address : null,
		];
		$customerError = CustomerValidator::create($customerData);

		if ($customerError !== null) {
			$this->fail($customerError);
		}

		$requestedItems = $input['items'] ?? null;

		if (!is_array($requestedItems) || count($requestedItems) < 1 || count($requestedItems) > 30) {
			$this->fail('Giỏ hàng không hợp lệ.');
		}

		$quantitiesByProduct = [];

		foreach ($requestedItems as $item) {
			if (!is_array($item)) {
				$this->fail('Sản phẩm trong giỏ hàng không hợp lệ.');
			}

			$productId = filter_var($item['product_id'] ?? null, FILTER_VALIDATE_INT);
			$quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);

			if (isset($item['variant']) && !is_string($item['variant'])) {
				$this->fail('Thông tin phiên bản sản phẩm không hợp lệ.');
			}

			$variant = trim((string) ($item['variant'] ?? ''));

			if ($productId === false || $productId < 1 || $quantity === false || $quantity < 1) {
				$this->fail('Sản phẩm hoặc số lượng không hợp lệ.');
			}

			if (strlen($variant) > 255) {
				$this->fail('Thông tin phiên bản sản phẩm không hợp lệ.');
			}

			if (!isset($quantitiesByProduct[$productId])) {
				$quantitiesByProduct[$productId] = [
					'quantity' => 0,
					'variants' => [],
				];
			}

			$quantitiesByProduct[$productId]['quantity'] += $quantity;

			if ($variant !== '') {
				$quantitiesByProduct[$productId]['variants'][$variant] = true;
			}
		}

		$productRepository = new ProductRepository();
		$orderItems = [];
		$responseItems = [];
		$totalAmount = 0;

		foreach ($quantitiesByProduct as $productId => $request) {
			$productData = $productRepository->getShow((string) $productId, true);
			$product = $productData[0][0] ?? null;

			if (!$product || ($product['status'] ?? null) !== 'active') {
				$this->fail('Một sản phẩm trong giỏ hàng hiện không còn được bán.');
			}

			$stock = (int) ($product['stock'] ?? 0);
			$purchaseId = (int) ($product['purchase_id'] ?? 0);

			if ($purchaseId < 1 || $stock < $request['quantity']) {
				$this->fail(
					$stock > 0
						? "Sản phẩm {$product['name']} chỉ còn {$stock} sản phẩm."
						: "Sản phẩm {$product['name']} hiện đã hết hàng."
				);
			}

			$variants = array_keys($request['variants']);
			$availableVariants = [];
			$variantLabelsByValue = [];

			foreach ($productData[2] ?? [] as $attribute) {
				$value = trim((string) ($attribute['attribute_value'] ?? ''));
				$label = trim(
					(string) ($attribute['attribute_name'] ?? '') . ': ' . $value
				);
				$availableVariants[$label] = true;
				$variantLabelsByValue[$value][] = $label;
			}

			foreach ($variants as $index => $variant) {
				if (isset($availableVariants[$variant])) {
					continue;
				}

				$matchingLabels = $variantLabelsByValue[$variant] ?? [];

				if (count($matchingLabels) !== 1) {
					$this->fail("Phiên bản của sản phẩm {$product['name']} không hợp lệ.");
				}

				$variants[$index] = $matchingLabels[0];
			}

			$variants = array_values(array_unique($variants));
			$basePrice = (float) ($product['price'] ?? 0);
			$salePrice = (float) ($product['sale_price'] ?? 0);
			$sellingPrice = $salePrice > 0 && $salePrice < $basePrice ? $salePrice : $basePrice;

			if ($sellingPrice <= 0) {
				$this->fail("Giá của sản phẩm {$product['name']} không hợp lệ.");
			}

			$productName = (string) $product['name'];
			$variantSummary = implode('; ', $variants);
			$storedProductName = $productName;
			$nameWithVariant = $variantSummary !== ''
				? $productName . ' (' . $variantSummary . ')'
				: $productName;

			if (strlen($nameWithVariant) <= 255) {
				$storedProductName = $nameWithVariant;
			}

			$lineTotal = round($sellingPrice * $request['quantity'], 2);
			$totalAmount += $lineTotal;

			$orderItems[] = [
				'purchase_id' => $purchaseId,
				'product_id' => (int) $productId,
				'product_name' => $storedProductName,
				'quantity' => $request['quantity'],
				'selling_price' => $sellingPrice,
				'subtotal_amount' => $lineTotal,
				'discount_amount' => 0,
				'vat_rate' => 0,
				'vat_amount' => 0,
				'total_amount' => $lineTotal,
				'is_gift' => 0,
			];

			$responseItems[] = [
				'product_name' => $productName,
				'quantity' => $request['quantity'],
				'selling_price' => $sellingPrice,
				'variant' => $variantSummary,
			];
		}

		$totalAmount = round($totalAmount, 2);
		$customerId = (new CustomerRepository())->create($customerData);
		$orderNote = 'Phương thức nhận hàng: ' . ($deliveryMethod === 'pickup' ? 'Nhận tại cửa hàng' : 'Giao hàng tận nơi');

		if ($address !== '') {
			$orderNote .= "\nĐịa chỉ nhận hàng: " . $address;
		}

		if ($note !== '') {
			$orderNote .= "\nGhi chú: " . $note;
		}

		$orderData = [
			'customer_id' => $customerId,
			'description' => 'Đơn hàng online',
			'note' => $orderNote,
			'status' => 'pending',
			'payment' => 'unpaid',
			'subtotal_amount' => $totalAmount,
			'vat_rate' => 0,
			'vat_amount' => 0,
			'total_amount' => $totalAmount,
			'paid_amount' => 0,
			'debt_amount' => $totalAmount,
			'items' => $orderItems,
		];
		$orderError = OrderValidator::create($orderData);

		if ($orderError !== null) {
			$this->fail($orderError);
		}

		$orderId = (new OrderRepository())->create($orderData);

		Response::json([
			'success' => true,
			'message' => 'Đặt hàng thành công.',
			'data' => [
				'id' => $orderId,
				'total_amount' => $totalAmount,
				'items' => $responseItems,
			],
		], 201);
	}

	private function fail(string $message): void
	{
		Response::error($message, 422);
	}
}
