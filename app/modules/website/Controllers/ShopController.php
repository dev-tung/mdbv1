<?php

namespace App\Website\Controllers;

use App\Core\View;
use App\Shop\Repositories\ProductRepository;

class ShopController
{
	private const PRODUCTS_PER_PAGE = 20;

	private ProductRepository $productRepository;

	public function __construct()
	{
		$this->productRepository = new ProductRepository();
	}

	public function index(): void
	{
		$input = $_GET;
		$categoryId = $this->positiveInteger($input['category_id'] ?? $input['category'] ?? null);
		$brandId = $this->positiveInteger($input['brand_id'] ?? null);
		$price = is_string($input['price'] ?? null) ? $input['price'] : '';
		$priceMin = null;
		$priceMax = null;

		if (preg_match('/^([0-9]+)-([0-9]+)$/', $price, $matches)) {
			$priceMin = (int) $matches[1];
			$priceMax = (int) $matches[2];
		} elseif (preg_match('/^[0-9]+$/', $price)) {
			$priceMin = (int) $price;
		} else {
			$price = '';
		}

		$status = is_string($input['status'] ?? null) ? $input['status'] : '';
		if (!in_array($status, ['active', 'inactive'], true)) {
			$status = '';
		}

		$keyword = is_string($input['keyword'] ?? null) ? trim($input['keyword']) : '';
		$currentPage = $this->positiveInteger($input['page'] ?? null) ?? 1;
		$filters = [
			'keyword' => $keyword,
			'category_id' => $categoryId,
			'brand_id' => $brandId,
			'status' => $status,
			'price_min' => $priceMin,
			'price_max' => $priceMax,
			'website' => 1,
			'page' => $currentPage,
			'per_page' => self::PRODUCTS_PER_PAGE,
		];

		$result = $this->productRepository->getList($filters);
		$totalProducts = (int) ($result[1][0]['total'] ?? 0);
		$totalPages = max(1, (int) ceil($totalProducts / self::PRODUCTS_PER_PAGE));
		$requestedPage = min($currentPage, $totalPages);

		if ($requestedPage !== $currentPage) {
			$currentPage = $requestedPage;
			$filters['page'] = $currentPage;
			$result = $this->productRepository->getList($filters);
		}

		$products = $result[0] ?? [];

		foreach ($products as &$product) {
			$product['thumbnail'] = $this->normalizeImagePath($product['thumbnail'] ?? null);
		}
		unset($product);

		$filters['keyword'] = $keyword;
		$filters['category_id'] = $categoryId;
		$filters['brand_id'] = $brandId;
		$filters['price'] = $price;
		$filters['status'] = $status;

		View::render('shop/index', compact('products', 'filters', 'currentPage', 'totalPages', 'totalProducts'));
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

	private function positiveInteger(mixed $value): ?int
	{
		$integer = filter_var($value, FILTER_VALIDATE_INT);

		return $integer !== false && $integer > 0 ? $integer : null;
	}
}