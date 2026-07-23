<?php
namespace App\Modules\Shop\Controllers;

use App\Core\View;

class PurchaseController
{
	public function index(): void
	{
		View::render('purchase/index');
	}

	public function form($id = null): void
	{
		View::render('purchase/form', compact('id'));
	}
}
