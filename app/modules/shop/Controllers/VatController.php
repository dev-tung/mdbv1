<?php

namespace App\Shop\Controllers;

use App\Core\View;
use App\Shop\Repositories\VatRepository;

class VatController
{
	private VatRepository $vatRepository;

	public function __construct()
	{
		$this->vatRepository = new VatRepository();
	}

  public function index(): void
  {
      $vat = $this->vatRepository->getVat();
      View::render('vat/index', compact('vat'));
  }

	public function pl011(): void
	{
    $vatPl011 = $this->vatRepository->getVatPl011();
		View::render('vat/pl011', compact('vatPl011'));
	}

	public function pl012(): void
	{
			$vatPl012 = $this->vatRepository->getVatPl012();
			View::render('vat/pl012', compact('vatPl012'));
	}

	public function pl204(): void
	{
		$vatPl204 = $this->vatRepository->getVatPl204();
		View::render('vat/pl204', compact('vatPl204'));
	}
}
