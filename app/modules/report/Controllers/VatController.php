<?php

namespace App\Report\Controllers;

use App\Core\View;

class VatController
{
	public function index(): void
		{
		View::render('vat/index');
	}
}
