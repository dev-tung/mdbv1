<?php

class CustomerEndpoint
{
	private CustomerRepository $customerRepository;

	public function __construct()
	{
		$this->customerRepository = new CustomerRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$filters = request_all();

		$result = $this->customerRepository->getList($filters);

		return Response::json([
			'success' => true,

			'message' => 'Lấy danh sách khách hàng thành công',

			'data' => $result,
		]);
	}

	// =========================
	// SHOW
	// =========================

	public function apiShow()
	{
		$id = request_id();

		$data = $this->customerRepository->findById($id);

		if (!$data) {
			return Response::json([
				'success' => false,

				'message' => 'Không tìm thấy khách hàng',

				'data' => null,
			]);
		}

		return Response::json([
			'success' => true,

			'message' => 'Lấy thông tin khách hàng thành công',

			'data' => $data,
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

		$input['created_by'] = Auth::id();

		$id = $this->customerRepository->create($input);

		return Response::json([
			'success' => true,

			'message' => 'Tạo khách hàng thành công',

			'data' => [
				'id' => $id,
			],
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

		$input['updated_by'] = Auth::id();

		$this->customerRepository->update($input);

		return Response::json([
			'success' => true,

			'message' => 'Cập nhật khách hàng thành công',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$id = request_id();

		$this->customerRepository->delete($id);

		return Response::json([
			'success' => true,

			'message' => 'Xóa khách hàng thành công',
		]);
	}
}
