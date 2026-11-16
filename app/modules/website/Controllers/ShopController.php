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
		$sales = $this->productRepository->getSale();
		View::render('shop/index', compact('sales'));
	}

	public function show(string $slug): void
	{
		$detail = $this->productRepository->getShow($slug, true);
		$product = $detail[0][0] ?? null;

		if (!$product) {
			http_response_code(404);
			View::render('errors/404');

			return;
		}

		$images = $detail[1] ?? [];
		$product['thumbnail'] = $this->normalizeImagePath($product['thumbnail'] ?? null);
		$images = array_map(function (array $image): array {
			$image['image'] = $this->normalizeImagePath($image['image'] ?? null);

			return $image;
		}, $images);
		$attributes = $detail[2] ?? [];

		View::render('shop/show', compact('product', 'images', 'attributes'));
	}

	private function normalizeImagePath(?string $path): string
	{
		if (!$path) {
			return '/assets/image/no-image.svg';
		}

		if (str_starts_with($path, '/') || preg_match('#^https?://#i', $path)) {
			return $path;
		}

		return '/' . $path;
	}
}