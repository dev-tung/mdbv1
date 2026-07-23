<?php
namespace App\Shop\Controllers;
use App\Core\View;

class SupplierController
{
	/**
	 * VIEW (UI shell - không load data suppliers nếu dùng fetch)
	 */
	public function index(): void
	{
		View::render('supplier/index');
	}

	public function form($id = null): void
	{
		View::render('supplier/form', compact('id'));
	}
}
