<?php
namespace App\Shop\Validators;

class ProductValidator
{
	// =========================
	// CREATE VALIDATION
	// =========================
	public static function create(array $input): ?string
	{
		// NAME
		if (empty(trim($input['name'] ?? ''))) {
			return 'Tên sản phẩm không được để trống';
		}

		// CATEGORY
		if (!isset($input['category_id']) || (int) $input['category_id'] <= 0) {
			return 'Danh mục không hợp lệ';
		}

		// BRAND
		if (!isset($input['brand_id']) || (int) $input['brand_id'] <= 0) {
			return 'Thương hiệu không hợp lệ';
		}

		// PRICE
		if (!isset($input['price']) || $input['price'] === '' || !is_numeric($input['price'])) {
			return 'Giá sản phẩm không hợp lệ';
		}

		if ((float) $input['price'] < 0) {
			return 'Giá sản phẩm không được âm';
		}

		// SALE PRICE
		if (isset($input['sale_price']) && $input['sale_price'] !== '' && !is_numeric($input['sale_price'])) {
			return 'Giá bán không hợp lệ';
		}

		if (isset($input['sale_price']) && $input['sale_price'] !== '' && (float) $input['sale_price'] < 0) {
			return 'Giá bán không được âm';
		}

		// CHECK SALE < PRICE
		if (
			isset($input['sale_price']) &&
			$input['sale_price'] !== '' &&
			(float) $input['sale_price'] > (float) $input['price']
		) {
			return 'Giá bán không được lớn hơn giá gốc';
		}

		// STATUS
		if (isset($input['status']) && !in_array($input['status'], ['active', 'inactive'], true)) {
			return 'Trạng thái không hợp lệ';
		}

		return null;
	}

	// =========================
	// UPDATE VALIDATION
	// =========================
	public static function update(array $input): ?string
	{
		if (!isset($input['id']) || (int) $input['id'] <= 0) {
			return 'ID sản phẩm không hợp lệ';
		}

		return self::create($input);
	}
}
