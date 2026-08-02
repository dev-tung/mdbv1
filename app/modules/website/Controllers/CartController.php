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
	public function out(): void
	{
		View::render('cart/out');
	}

	/**
	 * Success page
	 */
	public function success(): void
	{
		View::render('cart/success');
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
