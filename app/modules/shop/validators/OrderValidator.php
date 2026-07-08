<?php

class OrderValidator
{
    /**
     * Validate khi CREATE
     */
    public static function create(array $data): ?string
    {

        // customer_id
        // Có thể cho phép khách lẻ nên không bắt buộc

        if (
            isset($data['customer_id'])
            && (int) $data['customer_id'] < 0
        ) {

            return 'Khách hàng không hợp lệ';

        }

        // items

        if (
            empty($data['items'])
            || !is_array($data['items'])
        ) {

            return 'Danh sách sản phẩm không hợp lệ';

        }

        if (
            count($data['items']) === 0
        ) {

            return 'Phải có ít nhất 1 sản phẩm';

        }

        foreach ($data['items'] as $product) {

            // product_id

            if (
                empty($product['product_id'])
                || (int) $product['product_id'] <= 0
            ) {

                return 'Sản phẩm không hợp lệ';

            }

            // quantity

            if (
                !isset($product['quantity'])
                || (int) $product['quantity'] <= 0
            ) {

                return 'Số lượng sản phẩm không hợp lệ';

            }

            // selling_price

            if (
                !isset($product['selling_price'])
                || (float) $product['selling_price'] < 0
            ) {

                return 'Giá bán sản phẩm không hợp lệ';

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

        if (
            !isset($data['id'])
            || (int) $data['id'] <= 0
        ) {

            return 'ID không hợp lệ';

        }

        // customer

        if (
            !empty($data['customer_id'])
            && (int) $data['customer_id'] <= 0
        ) {

            return 'Khách hàng không hợp lệ';

        }

        return null;

    }
}
