<?php
namespace App\Shop\Validators;

class PurchaseValidator
{
	/**
	 * Validate khi CREATE
	 */
	public static function create(array $data): ?string
	{
		// supplier_id
		if (empty($data['supplier_id']) || (int) $data['supplier_id'] <= 0) {
			return 'Nhà cung cấp không hợp lệ';
		}

		// warehouse_id
		if (empty($data['warehouse_id']) || (int) $data['warehouse_id'] <= 0) {
			return 'Kho không hợp lệ';
		}

		// items
		if (empty($data['items']) || !is_array($data['items'])) {
			return 'Danh sách sản phẩm không hợp lệ';
		}

		if (count($data['items']) === 0) {
			return 'Phải có ít nhất 1 sản phẩm';
		}

		foreach ($data['items'] as $product) {
			if (empty($product['product_id']) || (int) $product['product_id'] <= 0) {
				return 'Sản phẩm không hợp lệ';
			}

			if (!isset($product['quantity']) || (int) $product['quantity'] <= 0) {
				return 'Số lượng sản phẩm không hợp lệ';
			}

			if (!isset($product['purchase_price']) || (float) $product['purchase_price'] < 0) {
				return 'Giá sản phẩm không hợp lệ';
			}
		}

		return null;
	}

	/**
	 * Validate khi UPDATE
	 */
	public static function update(array $data): ?string
	{
		// ID
		if (!isset($data['id']) || (int) $data['id'] <= 0) {
			return 'ID không hợp lệ';
		}

		if (!empty($data['supplier_id']) && (int) $data['supplier_id'] <= 0) {
			return 'Nhà cung cấp không hợp lệ';
		}

		return null;
	}
}
