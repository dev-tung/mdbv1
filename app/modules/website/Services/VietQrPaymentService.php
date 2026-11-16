<?php

namespace App\Website\Services;

use App\Core\Env;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use DomainException;
use RuntimeException;

class VietQrPaymentService
{
	public function createQr(int $orderId, float $amount): array
	{
		$bankName = trim((string) Env::get('PAYMENT_BANK_NAME', ''));
		$bankBin = trim((string) Env::get('PAYMENT_BANK_BIN', ''));
		$accountNumber = trim((string) Env::get('PAYMENT_ACCOUNT_NUMBER', ''));
		$accountName = trim((string) Env::get('PAYMENT_ACCOUNT_NAME', ''));

		if ($bankName === '' || $accountName === '') {
			throw new DomainException('Chưa cấu hình đầy đủ thông tin ngân hàng thanh toán.');
		}

		if (!preg_match('/^[0-9]{6}$/', $bankBin)) {
			throw new DomainException('Mã ngân hàng phải là BIN NAPAS gồm 6 chữ số.');
		}

		if (!preg_match('/^[A-Za-z0-9]{1,19}$/', $accountNumber)) {
			throw new DomainException('Số tài khoản ngân hàng không hợp lệ.');
		}

		if ($orderId < 1 || !is_finite($amount) || $amount <= 0) {
			throw new DomainException('Mã đơn hoặc số tiền thanh toán không hợp lệ.');
		}

		$qrAccountName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $accountName);

		if ($qrAccountName === false) {
			throw new DomainException('Tên chủ tài khoản không thể chuyển thành định dạng QR.');
		}

		$qrAccountName = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $qrAccountName)));

		if ($qrAccountName === '' || strlen($qrAccountName) > 25) {
			throw new DomainException('Tên chủ tài khoản dùng cho QR phải dài tối đa 25 ký tự.');
		}

		$transferContent = 'MDB-' . $orderId;
		$amountText = rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');

		if (strlen($amountText) > 13) {
			throw new DomainException('Số tiền vượt quá giới hạn của mã QR VietQR.');
		}

		$accountInfo = $this->field('00', 'A000000727')
			. $this->field(
				'01',
				$this->field('00', $bankBin)
					. $this->field('01', $accountNumber)
			)
			. $this->field('02', 'QRIBFTTA');
		$additionalData = $this->field('08', $transferContent);

		$payload = $this->field('00', '01')
			. $this->field('01', '12')
			. $this->field('38', $accountInfo)
			. $this->field('53', '704')
			. $this->field('54', $amountText)
			. $this->field('58', 'VN')
			. $this->field('59', $qrAccountName)
			. $this->field('62', $additionalData)
			. '6304';

		$payload .= $this->crc16($payload);

		$autoloadPath = PATH_ROOT . '/vendor/autoload.php';

		if (!is_file($autoloadPath)) {
			throw new RuntimeException('Thiếu thư viện tạo QR. Hãy chạy composer install.');
		}

		require_once $autoloadPath;

		$renderer = new ImageRenderer(
			new RendererStyle(320, 4),
			new SvgImageBackEnd()
		);
		$svg = (new Writer($renderer))->writeString($payload);

		return [
			'qr_data_uri' => 'data:image/svg+xml;base64,' . base64_encode($svg),
			'bank_name' => $bankName,
			'account_number' => $accountNumber,
			'account_name' => $accountName,
			'amount' => $amount,
			'transfer_content' => $transferContent,
		];
	}

	private function field(string $id, string $value): string
	{
		$length = strlen($value);

		if (!preg_match('/^[0-9]{2}$/', $id) || $length > 99) {
			throw new DomainException('Dữ liệu QR vượt quá định dạng VietQR cho phép.');
		}

		return $id . str_pad((string) $length, 2, '0', STR_PAD_LEFT) . $value;
	}

	private function crc16(string $payload): string
	{
		$crc = 0xFFFF;

		for ($index = 0, $length = strlen($payload); $index < $length; $index++) {
			$crc ^= ord($payload[$index]) << 8;

			for ($bit = 0; $bit < 8; $bit++) {
				$crc = ($crc & 0x8000) !== 0
					? (($crc << 1) ^ 0x1021)
					: ($crc << 1);
				$crc &= 0xFFFF;
			}
		}

		return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
	}
}
