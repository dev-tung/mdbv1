<?php

class CartController
{
    /**
     * Giỏ hàng page
     */
    public function index(): void
    {
        View::render('cart/index');
    }

    /**
     * Checkout page
     */
    public function checkout(): void
    {
        View::render('cart/checkout');
    }

    /**
     * Success page
     */
    public function success(): void
    {
        View::render('cart/success');
    }

    public function add(): void {}
    public function update(): void {}
    public function remove(): void {}
}
