<?php

class ShopController
{
    protected ProductModel $productModel;
    protected BrandModel $brandModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel   = new ProductModel();
        $this->brandModel   = new BrandModel();
        $this->categoryModel  = new CategoryModel();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getList();
        $brands = $this->brandModel->getList();

        View::render('shop/index', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function show(): void
    {
        $slug = $_SERVER['REQUEST_URI'];
        $slug = trim(parse_url($slug, PHP_URL_PATH), '/');
        $slug = str_replace('product/', '', $slug);

        $product = $this->productModel->findBySlugStock($slug);

        if (!$product) {
            http_response_code(404);
            exit('Product not found');
        }

        $category = $product['category_id']
            ? $this->categoryModel->findById($product['category_id'])
            : null;

        View::render('shop/show', compact('product', 'category'));
    }
}
