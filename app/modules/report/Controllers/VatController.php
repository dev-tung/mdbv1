<?php

namespace App\Report\Controllers;

use App\Core\View;

class VatController
{
	public function index(): void
		{
		View::render('vat/index');
	}

	public function pl011(): void
	{
		View::render('vat/pl011');
	}

	public function pl012(): void
	{
		View::render('vat/pl012');
	}

	public function pl204(): void
	{
		View::render('vat/pl204');
	}
}
