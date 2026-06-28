<?php

class CategoryEndpoint
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function apiList()
    {
        header('Content-Type: application/json');

        // Lấy tất cả category (thường dùng cho dropdown filter)
        $categories = $this->categoryModel->getList();

        echo json_encode([
            'data' => $categories
        ]);
    }
}