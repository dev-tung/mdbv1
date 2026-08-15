<?php

namespace App\Shop\Endpoints;

use App\Core\Response;
use App\Shop\Repositories\BrandRepository;

class BrandEndpoint
{
	private BrandRepository $brandRepository;

	public function __construct()
	{
		$this->brandRepository = new BrandRepository();
	}

	// =========================
	// LIST
	// =========================
	public function apiList()
	{
		$filters = request_all();

		$result = $this->brandRepository->getList($filters);

		return Response::json([
			'success' => true,
			'message' => 'Lấy danh sách thương hiệu thành công',
			'data' => $result,
		]);
	}

	// =========================
	// CREATE
	// =========================
	public function apiCreate()
	{
		$data = request_all();

		$name = trim($data['name'] ?? '');

		if ($name === '') {
			return Response::json([
				'success' => false,
				'message' => 'Tên thương hiệu không được để trống',
			], 422);
		}

		$brand = $this->brandRepository->create([
			'name' => $name,
		]);

		return Response::json([
			'success' => true,
			'message' => 'Thêm thương hiệu thành công',
			'data' => $brand[0] ?? null,
		]);
	}
}