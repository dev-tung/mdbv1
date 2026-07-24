<?php

namespace App\Shop\Controllers;
use App\Core\View;

class ReportController
{
	public function inventory(): void
	{
		View::render('report/inventory');
	}

	public function revenue(): void
	{
		View::render('report/revenue');
	}

	public function buyer(): void
	{
		View::render('report/buyer');
	}
}
