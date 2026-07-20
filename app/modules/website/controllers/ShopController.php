<?php

class ShopController
{
	protected ProductRepository $productRepository;

	protected BrandRepository $brandRepository;

	protected CategoryRepository $categoryRepository;


	public function __construct()
	{
		$this->productRepository = new ProductRepository();

		$this->brandRepository = new BrandRepository();

		$this->categoryRepository = new CategoryRepository();
	}


	public function index(): void
	{
		$categories = $this->categoryRepository->getList();

		$brands = $this->brandRepository->getList();


		View::render('shop/index', compact(
			'categories',
			'brands'
		));
	}



	public function show(): void
	{
		$slug = get_slug('product');


		$product = $this->productRepository->findBySlugStock($slug);


		if (!$product) {

			http_response_code(404);

			exit('Product not found');
		}


		$category = null;


		if (!empty($product['category_id'])) {

			$category = $this->categoryRepository
				->findById($product['category_id']);
		}


		View::render('shop/show', compact(
			'product',
			'category'
		));
	}
}