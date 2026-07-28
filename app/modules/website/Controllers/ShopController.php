<?php

namespace App\Website\Controllers;

use App\Core\View;
use App\Shop\Repositories\ProductRepository;

class ShopController
{
	private ProductRepository $productRepository;

	public function __construct()
	{
		$this->productRepository = new ProductRepository();
	}

	public function index(): void
	{
		View::render('shop/index');
	}

	public function show(string $slug): void
	{
		$result = $this->productRepository->getShow($slug, true);

		$product = $result[0][0] ?? null;
		$images = $result[1] ?? [];
		$attributes = $result[2] ?? [];

		View::render('shop/show', compact(
			'product',
			'images',
			'attributes',
		));
	}
}