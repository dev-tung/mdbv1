<?php

namespace App\Website\Validators;

class VietQrPaymentValidator
{
	public static function create(array $input): ?string
	{
		$bankName = trim((string) ($input['bank_name'] ?? ''));
		$bankBin = trim((string) ($input['bank_bin'] ?? ''));
		$accountNumber = trim((string) ($input['account_number'] ?? ''));
		$accountName = trim((string) ($input['account_name'] ?? ''));
		$orderId = filter_var($input['order_id'] ?? null, FILTER_VALIDATE_INT);
		$amount = $input['amount'] ?? null;

		if ($bankName === '' || $accountName === '') {
			return 'Chưa cấu hình đầy đủ thông tin ngân hàng thanh toán.';
		}

		if (!preg_match('/^[0-9]{6}$/', $bankBin)) {
			return 'Mã ngân hàng phải là BIN NAPAS gồm 6 chữ số.';
		}

		if (!preg_match('/^[A-Za-z0-9]{1,19}$/', $accountNumber)) {
			return 'Số tài khoản ngân hàng không hợp lệ.';
		}

		if (
			$orderId === false ||
			$orderId < 1 ||
			!is_numeric($amount) ||
			!is_finite((float) $amount) ||
			(float) $amount <= 0
		) {
			return 'Mã đơn hoặc số tiền thanh toán không hợp lệ.';
		}

		$qrAccountName = self::formatAccountName($accountName);

		if ($qrAccountName === false) {
			return 'Tên chủ tài khoản không thể chuyển thành định dạng QR.';
		}

		if ($qrAccountName === '' || strlen($qrAccountName) > 25) {
			return 'Tên chủ tài khoản dùng cho QR phải dài tối đa 25 ký tự.';
		}

		$amountText = rtrim(rtrim(number_format((float) $amount, 2, '.', ''), '0'), '.');

		if (strlen($amountText) > 13) {
			return 'Số tiền vượt quá giới hạn của mã QR VietQR.';
		}

		return null;
	}

	public static function formatAccountName(string $accountName): string|false
	{
		$transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $accountName);

		if ($transliterated === false) {
			return false;
		}

		$printableName = preg_replace('/[^\x20-\x7E]/', '', $transliterated);

		if ($printableName === null) {
			return false;
		}

		return strtoupper(trim($printableName));
	}

	public static function validateField(string $id, string $value): ?string
	{
		if (!preg_match('/^[0-9]{2}$/', $id) || strlen($value) > 99) {
			return 'Dữ liệu QR vượt quá định dạng VietQR cho phép.';
		}

		return null;
	}
}
