<?php
namespace App\Modules\Shop\Controllers;

use App\Core\View;

class OrderController
{
	public function index(): void
	{
		View::render('order/index');
	}

	public function form($id = null): void
	{
		View::render('order/form', compact('id'));
	}
}
