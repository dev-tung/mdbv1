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

	public function sale(): void
	{
		$stockInput = $_GET['s'] ?? '90';
		$profitInput = $_GET['p'] ?? '30%';
		$limitInput = $_GET['l'] ?? '15';
		$categoryInput = $_GET['c'] ?? null;

		if (!is_string($stockInput) || !ctype_digit($stockInput)) {
			http_response_code(400);
			echo 'Tham số s phải là số ngày không âm.';
			return;
		}

		if (
			!is_string($profitInput)
			|| !preg_match('/^(\d+(?:\.\d+)?)%?$/', $profitInput, $profitMatch)
			|| (float) $profitMatch[1] > 100
		) {
			http_response_code(400);
			echo 'Tham số p phải là tỷ lệ phần trăm từ 0 đến 100.';
			return;
		}

		if (!is_string($limitInput) || !ctype_digit($limitInput) || (int) $limitInput < 1) {
			http_response_code(400);
			echo 'Tham số l phải là số lượng sản phẩm lớn hơn 0.';
			return;
		}

		if ($categoryInput !== null && (!is_string($categoryInput) || !ctype_digit($categoryInput) || (int) $categoryInput < 1)) {
			http_response_code(400);
			echo 'Tham số c phải là mã danh mục lớn hơn 0.';
			return;
		}

		$stockDays = (int) $stockInput;
		$profitPercent = (float) $profitMatch[1];
		$productLimit = (int) $limitInput;
		$categoryId = $categoryInput === null ? null : (int) $categoryInput;

		$result = $this->productRepository->getSale([
			'website' => 0,
			'stock_days' => $stockDays,
		]);

		$products = $result[0] ?? [];
		$products = is_array($products) ? $products : [];
		$products = array_values(array_filter(
			$products,
			static function (array $product) use ($categoryId, $stockDays): bool {
				return ($categoryId === null || (int) ($product['category_id'] ?? 0) === $categoryId)
					&& ($product['status'] ?? '') === 'active'
					&& (float) ($product['price'] ?? 0) > 0
					&& (float) ($product['import_price'] ?? 0) > 0
					&& (int) ($product['stock'] ?? 0) > 0
					&& (int) ($product['days_in_stock'] ?? -1) >= $stockDays;
			},
		));

		if (isset($result[0]) && is_array($result[0])) {
			$result[0] = $products;
		}

		View::render('shop/sale', compact(
			'result',
			'stockDays',
			'profitPercent',
			'productLimit',
			'categoryId',
		));
	}
}