<?php

namespace App\Shop\Controllers;

class CustomerController
{
	/**
	 * VIEW (UI shell - không load data customers nếu dùng fetch)
	 */
	public function index(): void
	{
		View::render('customer/index');
	}

	public function form($id = null): void
	{
		View::render('customer/form', compact('id'));
	}
}
