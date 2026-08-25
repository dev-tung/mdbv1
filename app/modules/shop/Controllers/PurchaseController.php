<?php

namespace App\Shop\Controllers;

use App\Core\View;
use App\Shop\Repositories\PurchaseRepository;

class PurchaseController
{
	private PurchaseRepository $purchaseRepository;

	public function __construct()
	{
		$this->purchaseRepository = new PurchaseRepository();
	}

	public function index(): void
	{
		View::render('purchase/index');
	}

	public function form($id = null): void
	{
		View::render('purchase/form', compact('id'));
	}

	public function vat(): void
	{
		View::render('purchase/vat/index');
	}

	public function pl011(): void
	{
		View::render('purchase/vat/pl011');
	}

	public function pl012(): void
	{
			$vatPl012 = $this->purchaseRepository->getVatPl012();
			View::render('purchase/vat/pl012', compact('vatPl012'));
	}

	public function pl204(): void
	{
		$vatPl204 = $this->purchaseRepository->getVatPl204();
		View::render('purchase/vat/pl204', compact('vatPl204'));
	}
}
