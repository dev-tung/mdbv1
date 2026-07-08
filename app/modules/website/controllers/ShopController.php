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

        View::render('shop/index', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function show(): void
    {
        $slug = $_SERVER['REQUEST_URI'];
        $slug = trim(parse_url($slug, PHP_URL_PATH), '/');
        $slug = str_replace('product/', '', $slug);

        $product = $this->productRepository->findBySlugStock($slug);

        if (!$product) {
            http_response_code(404);
            exit('Product not found');
        }

        $category = $product['category_id']
            ? $this->categoryRepository->findById($product['category_id'])
            : null;

        View::render('shop/show', compact('product', 'category'));
    }
}
