<?php

namespace App\Website\Controllers;

use App\Core\View;

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
	 * Out page
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

	public function payment(): void
	{
		View::render('cart/payment');
	}

	public function status(): void
	{
		View::render('cart/status');
	}

	public function add(): void
	{
	}

	public function update(): void
	{
	}

	public function remove(): void
	{
	}
}
