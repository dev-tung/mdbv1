<?php
namespace App\Shop\Validators;

class SupplierValidator
{
	// =========================
	// CREATE VALIDATION
	// =========================

	public static function create(array $input): ?string
	{
		// NAME
		if (empty(trim($input['name'] ?? ''))) {
			return 'Tên nhà cung cấp không được để trống';
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
			return 'ID nhà cung cấp không hợp lệ';
		}

		return self::create($input);
	}
}
