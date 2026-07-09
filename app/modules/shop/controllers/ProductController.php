<?php

class ProductController
{
	protected ProductRepository $productRepository;

	protected CategoryRepository $categoryRepository;

	public function __construct()
	{
		$this->productRepository = new ProductRepository();
		$this->categoryRepository = new CategoryRepository();
	}

	/**
	 * VIEW (UI shell - không load data products nếu dùng fetch)
	 */
	public function index(): void
	{
		$categories = $this->categoryRepository->getList();

		View::render('product/index', [
			'categories' => $categories,
		]);
	}

	public function create(): void
	{
		View::render('product/create');
	}

	public function edit($id): void
	{
		($product = $this->productRepository->findById((int) $id)) or die('Product not found');

		View::render('product/edit', compact('id', 'product'));
	}
}
