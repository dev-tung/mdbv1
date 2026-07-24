<?php

namespace App\Website\Controllers;

use App\Core\View;
use App\Shop\Repositories\CategoryRepository;

class HomeController
{
	protected CategoryRepository $categoryRepository;

	public function __construct()
	{
		$this->categoryRepository = new CategoryRepository();
	}

	public function index(): void
	{
		$categories = $this->categoryRepository->getList();

		View::render('home/index', compact('categories'));
	}
}
