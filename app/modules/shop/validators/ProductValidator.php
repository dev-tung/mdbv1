<?php

class ProductValidator
{
	// =========================
	// CREATE VALIDATION
	// =========================
	public static function create(array $input): ?string
	{
		if (empty(trim($input['name'] ?? ''))) {
			return 'Tên sản phẩm không được để trống';
		}

		if (!isset($input['category_id']) || (int) $input['category_id'] <= 0) {
			return 'Category không hợp lệ';
		}

		if (isset($input['price']) && !is_numeric($input['price'])) {
			return 'Giá sản phẩm không hợp lệ';
		}

		if (isset($input['sale_price']) && !is_numeric($input['sale_price'])) {
			return 'Giá sale không hợp lệ';
		}

		if (isset($input['status']) && !in_array((int) $input['status'], [0, 1])) {
			return 'Status không hợp lệ';
		}

		return null;
	}

	// =========================
	// UPDATE VALIDATION
	// =========================
	public static function update(array $input): ?string
	{
		if (!isset($input['id']) || (int) $input['id'] <= 0) {
			return 'ID không hợp lệ';
		}

		return self::create($input);
	}
}
