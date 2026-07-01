<?php

class PurchaseValidator
{
    /**
     * Validate khi CREATE
     */
    public static function create(array $data): ?string
    {
        // supplier_id
        if (empty($data['supplier_id']) || (int)$data['supplier_id'] <= 0) {
            return 'Nhà cung cấp không hợp lệ';
        }

        // warehouse_id
        if (empty($data['warehouse_id']) || (int)$data['warehouse_id'] <= 0) {
            return 'Kho không hợp lệ';
        }

        // products (FIX từ items → products)
        if (empty($data['products']) || !is_array($data['products'])) {
            return 'Danh sách sản phẩm không hợp lệ';
        }

        if (count($data['products']) === 0) {
            return 'Phải có ít nhất 1 sản phẩm';
        }

        foreach ($data['products'] as $product) {

            // FIX: id thay vì product_id
            if (empty($product['id']) || (int)$product['id'] <= 0) {
                return 'Sản phẩm không hợp lệ';
            }

            if (!isset($product['quantity']) || (int)$product['quantity'] <= 0) {
                return 'Số lượng sản phẩm không hợp lệ';
            }

            if (!isset($product['price']) || (float)$product['price'] < 0) {
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
        if (!isset($data['id']) || (int)$data['id'] <= 0) {
            return 'ID không hợp lệ';
        }

        if (!empty($data['supplier_id']) && (int)$data['supplier_id'] <= 0) {
            return 'Nhà cung cấp không hợp lệ';
        }

        return null;
    }
}