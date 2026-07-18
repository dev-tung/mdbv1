<?php

class CustomerEndpoint
{
	private readonly CustomerRepository $customerRepository;

	public function __construct()
	{
		$this->customerRepository = new CustomerRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->customerRepository->getList(request_all());

		return Response::json([
			'success' => true,
			'data' => $data,
		]);
	}

	// =========================
	// SHOW
	// =========================

	public function apiShow()
	{
		$customer = $this->customerRepository->findById(request_id());

		if (!$customer) {
			return Response::json([
				'success' => false,
				'message' => 'Customer not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $customer,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();
		$error = CustomerValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$id = $this->customerRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Thêm khách hàng thành công!',
			'id' => $id,
			'redirect' => '/admin/customers',
		]);
	}

	// =========================
	// UPDATE
	// =========================

	public function apiUpdate()
	{
		$input = request_all();

		$error = CustomerValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$this->customerRepository->update((int) ($input['id'] ?? 0), $input);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật khách hàng thành công!',
			'redirect' => '/admin/customers',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$this->customerRepository->delete(request_id());

		return Response::json([
			'success' => true,
			'message' => 'Xóa khách hàng thành công!',
		]);
	}
}
