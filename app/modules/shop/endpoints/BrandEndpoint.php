<?php

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
}
