<?php

namespace App\Shop\Endpoints;

use App\Core\Response;
use App\Shop\Repositories\CategoryRepository;

class CategoryEndpoint
{
	private CategoryRepository $categoryRepository;

	public function __construct()
	{
		$this->categoryRepository = new CategoryRepository();
	}

	// =========================
	// LIST
	// =========================
	public function apiList()
	{
		$filters = request_all();

		$result = $this->categoryRepository->getList($filters);

		return Response::json([
			'success' => true,
			'message' => 'Lấy danh sách danh mục thành công',
			'data' => $result,
		]);
	}
}
