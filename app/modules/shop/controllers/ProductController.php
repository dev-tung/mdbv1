<?php

class ProductController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel   = new ProductModel();
        $this->categoryModel  = new CategoryModel();
    }

    /**
     * VIEW (UI shell - không load data products nếu dùng fetch)
     */
    public function index(): void
    {
        $categories = $this->categoryModel->getList();

        View::render('product/index', [
            'categories' => $categories
        ]);
    }

    public function create(): void
    {
        View::render('product/create');
    }

    public function edit($id): void
    {
        $product = $this->productModel->findById((int)$id)
            or die('Product not found');

        View::render('product/edit', compact('id', 'product'));
    }
}