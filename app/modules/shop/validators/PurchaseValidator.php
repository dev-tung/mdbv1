<?php

class PurchaseValidator
{
    public static function validate(array $data): ?string
    {
        $supplier_id  = (int)($data['supplier_id'] ?? 0);
        $warehouse_id = (int)($data['warehouse_id'] ?? 0);
        $items        = $data['products'] ?? [];

        if ($supplier_id <= 0) {
            return 'Nhà cung cấp không hợp lệ';
        }

        if ($warehouse_id <= 0) {
            return 'Kho không hợp lệ';
        }

        if (empty($items)) {
            return 'Chưa có sản phẩm';
        }

        foreach ($items as $index => $item) {

            $product_id = (int)($item['product_id'] ?? $item['id'] ?? 0);
            $quantity   = (int)($item['quantity'] ?? 0);

            if ($product_id <= 0) {
                return 'Sản phẩm ở dòng ' . ($index + 1) . ' không hợp lệ';
            }

            if ($quantity <= 0) {
                return 'Số lượng ở dòng ' . ($index + 1) . ' không hợp lệ';
            }
        }

        return null;
    }
}