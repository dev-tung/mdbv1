<?php

class CategoryEndpoint
{
    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function apiList()
    {
        header('Content-Type: application/json');

        // Lấy tất cả category (thường dùng cho dropdown filter)
        $categories = $this->categoryRepository->getList();

        echo json_encode([
            'data' => $categories
        ]);
    }
}