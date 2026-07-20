<?php

class HomeController
{
	protected CategoryRepository $categoryRepository;

	public function __construct()
	{
		$this->categoryRepository = new CategoryRepository();
	}

	public function index(): void
	{
		// danh mục
		$categories = $this->categoryRepository->getList();
		View::render('home/index', compact('categories'));
	}
}
