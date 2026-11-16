<?php

namespace App\Website\Services;

use App\Core\Env;
use App\Website\Validators\VietQrPaymentValidator;
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

		$error = VietQrPaymentValidator::create([
			'bank_name' => $bankName,
			'bank_bin' => $bankBin,
			'account_number' => $accountNumber,
			'account_name' => $accountName,
			'order_id' => $orderId,
			'amount' => $amount,
		]);

		if ($error !== null) {
			throw new DomainException($error);
		}

		$qrAccountName = VietQrPaymentValidator::formatAccountName($accountName);

		if ($qrAccountName === false) {
			throw new DomainException('Tên chủ tài khoản không thể chuyển thành định dạng QR.');
		}

		$transferContent = 'MDB-' . $orderId;
		$amountText = rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');

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
		$error = VietQrPaymentValidator::validateField($id, $value);

		if ($error !== null) {
			throw new DomainException($error);
		}

		return $id . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
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
