<?php
namespace App\CRM\Validators;

class CustomerValidator
{
	// =========================
	// CREATE VALIDATION
	// =========================

	public static function create(array $input): ?string
	{
		// NAME
		if (empty(trim($input['name'] ?? ''))) {
			return 'Tên khách hàng không được để trống';
		}

		// PHONE
		if (!empty($input['phone']) && !preg_match('/^[0-9+\-\s()]{8,20}$/', $input['phone'])) {
			return 'Số điện thoại không hợp lệ';
		}

		// EMAIL
		if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
			return 'Email không hợp lệ';
		}

		return null;
	}

	// =========================
	// UPDATE VALIDATION
	// =========================

	public static function update(array $input): ?string
	{
		if (!isset($input['id']) || (int) $input['id'] <= 0) {
			return 'ID khách hàng không hợp lệ';
		}

		return self::create($input);
	}

	public static function equipment(array $input): ?string
	{
		foreach (['rackets', 'shoes'] as $key) {
			if (array_key_exists($key, $input) && !is_array($input[$key])) {
				return 'Thông tin vợt và giày không hợp lệ';
			}
		}

		$rackets = $input['rackets'] ?? [];
		$shoes = $input['shoes'] ?? [];

		if (count($rackets) > 50 || count($shoes) > 50) {
			return 'Mỗi khách hàng chỉ có thể lưu tối đa 50 vợt và 50 đôi giày';
		}

		foreach ($rackets as $racket) {
			if (!is_array($racket) || !self::validId($racket['id'] ?? null)) {
				return 'Thông tin vợt không hợp lệ';
			}

			if (
				!self::validText($racket['brand'] ?? '', 100) ||
				!self::validText($racket['model'] ?? '', 150) ||
				!self::validText($racket['note'] ?? '', 1000)
			) {
				return 'Thông tin vợt vượt quá độ dài cho phép';
			}

			$hasRacketDetails = self::hasValue($racket, ['brand', 'model', 'note']);
			$stringings = $racket['stringings'] ?? [];

			if (!is_array($stringings) || count($stringings) > 100) {
				return 'Lịch sử căng cước không hợp lệ';
			}

			foreach ($stringings as $record) {
				if (!is_array($record) || !self::validId($record['id'] ?? null)) {
					return 'Thông tin lần căng cước không hợp lệ';
				}

				if (
					!self::validText($record['string_type'] ?? '', 100) ||
					!self::validText($record['tension'] ?? '', 40) ||
					!self::validText($record['note'] ?? '', 1000)
				) {
					return 'Thông tin lần căng cước vượt quá độ dài cho phép';
				}

				$date = trim((string) ($record['strung_at'] ?? ''));
				if ($date !== '' && !self::validDate($date)) {
					return 'Ngày căng cước không hợp lệ';
				}

				if (
					empty($record['id']) &&
					self::hasValue($record, ['string_type', 'tension', 'strung_at', 'note']) &&
					(
						trim((string) ($record['string_type'] ?? '')) === '' ||
						trim((string) ($record['tension'] ?? '')) === ''
					)
				) {
					return 'Vui lòng nhập loại cước và mức căng cho lần căng mới';
				}
			}

			if (
				empty($racket['id']) &&
				($hasRacketDetails || count($stringings) > 0) &&
				trim((string) ($racket['brand'] ?? '')) === '' &&
				trim((string) ($racket['model'] ?? '')) === ''
			) {
				return 'Vui lòng nhập thương hiệu hoặc tên vợt';
			}
		}

		foreach ($shoes as $shoe) {
			if (!is_array($shoe) || !self::validId($shoe['id'] ?? null)) {
				return 'Thông tin giày không hợp lệ';
			}

			if (
				!self::validText($shoe['brand'] ?? '', 100) ||
				!self::validText($shoe['model'] ?? '', 150) ||
				!self::validText($shoe['size'] ?? '', 20) ||
				!self::validText($shoe['note'] ?? '', 1000)
			) {
				return 'Thông tin giày vượt quá độ dài cho phép';
			}

			if (
				empty($shoe['id']) &&
				self::hasValue($shoe, ['brand', 'model', 'size', 'note']) &&
				trim((string) ($shoe['brand'] ?? '')) === '' &&
				trim((string) ($shoe['model'] ?? '')) === ''
			) {
				return 'Vui lòng nhập thương hiệu hoặc tên giày';
			}
		}

		return null;
	}

	private static function validId(mixed $id): bool
	{
		return $id === null || $id === '' || (is_numeric($id) && (int) $id > 0);
	}

	private static function validText(mixed $value, int $maxLength): bool
	{
		if (!is_scalar($value)) {
			return false;
		}

		return preg_match('/^.{0,' . $maxLength . '}$/us', (string) $value) === 1;
	}

	private static function hasValue(array $data, array $keys): bool
	{
		foreach ($keys as $key) {
			if (trim((string) ($data[$key] ?? '')) !== '') {
				return true;
			}
		}

		return false;
	}

	private static function validDate(string $date): bool
	{
		$parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

		return $parsed !== false && $parsed->format('Y-m-d') === $date;
	}
}
