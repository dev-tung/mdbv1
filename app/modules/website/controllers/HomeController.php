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
		dd('Website is in maintainent mode!');
		// danh mục
		$categories = $this->categoryRepository->getList();

		View::render('home/index', [
			'categories' => $categories,
		]);
	}
}
