<?php

class CustomerEndpoint
{
	protected CustomerRepository $customerRepository;

	public function __construct()
	{
		$this->customerRepository = new CustomerRepository();
	}

	// =========================
	// LIST
	// =========================
	public function apiList()
	{
		$page = max(1, (int) ($_GET['page'] ?? 1));
		$limit = Config::get('pagination', 'default_per_page');

		$filters = request_filters(['keyword', 'group_id']);

		$data = $this->customerRepository->getList($filters, $limit, ($page - 1) * $limit);

		$total = $this->customerRepository->count($filters);

		return Response::json([
			'success' => true,
			'data' => $data,
			'meta' => [
				'page' => $page,
				'total' => $total,
				'totalPages' => ceil($total / $limit),
				'perPage' => $limit,
			],
		]);
	}

	// =========================
	// SHOW
	// =========================
	public function apiShow($id)
	{
		$id = (int) $id;

		if ($id <= 0) {
			return Response::json([
				'success' => false,
				'message' => 'ID không hợp lệ',
			]);
		}

		$customer = $this->customerRepository->findById($id);

		if (!$customer) {
			return Response::json([
				'success' => false,
				'message' => 'Không tìm thấy khách hàng',
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
		$input = json_decode(file_get_contents('php://input'), true);

		$name = trim($input['name'] ?? '');
		$phone = trim($input['phone'] ?? '');
		$email = trim($input['email'] ?? '');
		$group = (int) ($input['group_id'] ?? 0);
		$address = trim($input['address'] ?? '');
		$description = trim($input['description'] ?? '');

		if ($name === '') {
			return Response::json([
				'success' => false,
				'message' => 'Tên khách hàng không hợp lệ',
			]);
		}

		$id = $this->customerRepository->create([
			'name' => $name,
			'phone' => $phone,
			'email' => $email,
			'group_id' => $group,
			'address' => $address,
			'description' => $description,
		]);

		return Response::json([
			'success' => true,
			'message' => 'Tạo khách hàng thành công',
			'id' => $id,
		]);
	}

	// =========================
	// UPDATE
	// =========================
	public function apiUpdate()
	{
		$input = json_decode(file_get_contents('php://input'), true);

		$id = (int) ($input['id'] ?? 0);

		if ($id <= 0) {
			return Response::json([
				'success' => false,
				'message' => 'ID không hợp lệ',
			]);
		}

		$data = [
			'name' => trim($input['name'] ?? ''),
			'phone' => trim($input['phone'] ?? ''),
			'email' => trim($input['email'] ?? ''),
			'group_id' => (int) ($input['group_id'] ?? 0),
			'address' => trim($input['address'] ?? ''),
			'description' => trim($input['description'] ?? ''),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$updated = $this->customerRepository->updateById($id, $data);

		return Response::json([
			'success' => $updated > 0,
			'message' => $updated > 0 ? 'Cập nhật thành công' : 'Không tìm thấy khách hàng',
		]);
	}

	// =========================
	// DELETE
	// =========================
	public function apiDelete()
	{
		$id = (int) ($_POST['id'] ?? 0);

		if ($id <= 0) {
			return Response::json([
				'success' => false,
				'message' => 'ID không hợp lệ',
			]);
		}

		$deleted = $this->customerRepository->deleteById($id);

		return Response::json([
			'success' => $deleted > 0,
			'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy khách hàng',
		]);
	}
}
