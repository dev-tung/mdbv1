<?php

namespace App\CRM\Endpoints;

use App\Core\Response;
use App\CRM\Repositories\CustomerGroupRepository;

class CustomerGroupEndpoint
{
	private readonly CustomerGroupRepository $customerGroupRepository;

	public function __construct()
	{
		$this->customerGroupRepository = new CustomerGroupRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->customerGroupRepository->getList(request_all());

		return Response::json([
			'success' => true,
			'data' => $data,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();

		$name = trim($input['name'] ?? '');

		if ($name === '') {
			return Response::json([
				'success' => false,
				'message' => 'Tên nhóm khách hàng không được để trống',
			]);
		}

		$id = $this->customerGroupRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Thêm nhóm khách hàng thành công!',
			'id' => $id,
		]);
	}
}