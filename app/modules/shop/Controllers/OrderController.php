<?php

namespace App\Shop\Controllers;

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

	public function onlineIndex(): void
	{
		View::render('order/online-index');
	}

	public function onlineShow($id): void
	{
		View::render('order/online-detail', compact('id'));
	}
}
