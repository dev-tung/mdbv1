<?php

class OrderValidator
{
    /**
     * Validate khi CREATE
     */
    public static function create(array $data): ?string
    {
        // customer_id
        if (empty($data['customer_id']) || (int)$data['customer_id'] <= 0) {
            return 'Nhà cung cấp không hợp lệ';
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
            if (empty($product['product_id']) || (int)$product['product_id'] <= 0) {
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

        if (!empty($data['customer_id']) && (int)$data['customer_id'] <= 0) {
            return 'Nhà cung cấp không hợp lệ';
        }

        if (!empty($data['warehouse_id']) && (int)$data['warehouse_id'] <= 0) {
            return 'Kho không hợp lệ';
        }

        return null;
    }
}