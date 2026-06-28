<?php

class HomeController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index(): void
    {
        // Danh mục sản phẩm
        $categories = $this->categoryModel->getList();

        View::render('home/index', [
            'categories'       => $categories,
            // 'newProducts'    => $newProducts
        ]);
    }
}