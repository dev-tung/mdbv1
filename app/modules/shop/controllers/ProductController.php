<?php
namespace App\Modules\Shop\Controllers;

use App\Core\View;

class ProductController
{
	/**
	 * VIEW (UI shell - không load data products nếu dùng fetch)
	 */
	public function index(): void
	{
		View::render('product/index');
	}

	public function form($id = null): void
	{
		View::render('product/form', compact('id'));
	}
}
