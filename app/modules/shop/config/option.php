<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PURCHASE WORKFLOW (quy trình nhập hàng)
    |--------------------------------------------------------------------------
    */
    'purchase_status' => [
        'draft' => [
            'label' => 'Nháp',
            'color' => 'danger',
        ],
        'confirmed' => [
            'label' => 'Đang chờ hàng',
            'color' => 'danger',
        ],
        'received' => [
            'label' => 'Đã nhận hàng',
            'color' => 'default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS (thanh toán)
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'unpaid' => [
            'label' => 'Chưa thanh toán',
            'color' => 'danger',
        ],
        'partial' => [
            'label' => 'Thanh toán một phần',
            'color' => 'danger',
        ],
        'paid' => [
            'label' => 'Đã thanh toán',
            'color' => 'default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS (bán hàng)
    |--------------------------------------------------------------------------
    */
    'order_status' => [
        'pending' => [
            'label' => 'Chờ xử lý',
            'color' => 'danger',
        ],
        'completed' => [
            'label' => 'Đã hoàn thành',
            'color' => 'default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PRODUCT PRICE RANGE
    |--------------------------------------------------------------------------
    */
    'price_range' => [
        'lt1' => [
            'label' => 'Dưới 1 triệu',
            'min'   => 0,
            'max'   => 1000000,
        ],

        '1-3' => [
            'label' => '1 - 3 triệu',
            'min'   => 1000000,
            'max'   => 3000000,
        ],

        '3-5' => [
            'label' => '3 - 5 triệu',
            'min'   => 3000000,
            'max'   => 5000000,
        ],

        'gt5' => [
            'label' => 'Trên 5 triệu',
            'min'   => 5000000,
            'max'   => null,
        ],
    ],

];