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

        // items
        if (empty($data['items']) || !is_array($data['items'])) {
            return 'Danh sách sản phẩm không hợp lệ';
        }

        if (count($data['items']) === 0) {
            return 'Phải có ít nhất 1 sản phẩm';
        }

        foreach ($data['items'] as $item) {

            if (empty($item['product_id']) || (int)$item['product_id'] <= 0) {
                return 'Sản phẩm không hợp lệ';
            }

            if (!isset($item['quantity']) || (int)$item['quantity'] <= 0) {
                return 'Số lượng sản phẩm không hợp lệ';
            }

            if (!isset($item['price']) || (float)$item['price'] < 0) {
                return 'Giá sản phẩm không hợp lệ';
            }
        }

        // payment
        if (isset($data['payment']) && !in_array($data['payment'], ['cash', 'debt', 'transfer'])) {
            return 'Phương thức thanh toán không hợp lệ';
        }

        // status (optional)
        if (isset($data['status']) && !in_array($data['status'], ['draft', 'confirmed', 'done'])) {
            return 'Trạng thái không hợp lệ';
        }

        return null;
    }

    /**
     * Validate khi UPDATE
     */
    public static function update(array $data): ?string
    {
        // reuse logic create (trừ items có thể optional tuỳ bạn)
        if (!empty($data['supplier_id']) && (int)$data['supplier_id'] <= 0) {
            return 'Nhà cung cấp không hợp lệ';
        }

        if (!empty($data['warehouse_id']) && (int)$data['warehouse_id'] <= 0) {
            return 'Kho không hợp lệ';
        }

        if (isset($data['payment']) && !in_array($data['payment'], ['cash', 'debt', 'transfer'])) {
            return 'Phương thức thanh toán không hợp lệ';
        }

        if (isset($data['status']) && !in_array($data['status'], ['draft', 'confirmed', 'done'])) {
            return 'Trạng thái không hợp lệ';
        }

        return null;
    }
}