<?php

class HomeController
{
    protected ProductRepository $productRepository;
    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->productRepository  = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function index(): void
    {
        // Danh mục sản phẩm
        $categories = $this->categoryRepository->getList();

        View::render('home/index', [
            'categories'       => $categories,
            // 'newProducts'    => $newProducts
        ]);
    }
}