<?php

namespace App\Website\Controllers;

use App\Core\View;

class PageController
{
	public function string(): void
	{
		View::render('pages/string');
	}

	public function affiliate(): void
	{
		View::render('pages/affiliate');
	}

	public function career(): void
	{
		View::render('pages/career');
	}

	public function contact(): void
	{
		View::render('pages/contact');
	}

	public function warrantyPolicy(): void
	{
		View::render('pages/warranty-policy');
	}

	public function shippingPolicy(): void
	{
		View::render('pages/shipping-policy');
	}

	public function returnPolicy(): void
	{
		View::render('pages/return-policy');
	}
}