<?php

class BrandEndpoint
{
	protected BrandRepository $brandRepository;

	public function __construct()
	{
		$this->brandRepository = new BrandRepository();
	}

	// =========================
	// LIST
	// =========================
	public function apiList()
	{
		$page = max(1, (int) ($_GET['page'] ?? 1));
		$limit = Config::get('pagination', 'default_per_page');

		$filters = request_filters(['keyword', 'status']);

		$brands = $this->brandRepository->getList(
			$filters,
			$limit,
			($page - 1) * $limit,
		);

		$total = $this->brandRepository->count($filters);

		return Response::json([
			'data' => $brands,
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
		if ($id <= 0) {
			return Response::json([
				'success' => false,
				'message' => 'ID không hợp lệ',
			]);
		}

		$brand = $this->brandRepository->findById($id);

		if (!$brand) {
			return Response::json([
				'success' => false,
				'message' => 'Không tìm thấy thương hiệu',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $brand,
		]);
	}

	// =========================
	// CREATE
	// =========================
	public function apiCreate()
	{
		$data = [
			'name' => trim($_POST['name'] ?? ''),
			'description' => trim($_POST['description'] ?? ''),
			'status' => (int) ($_POST['status'] ?? 1),
			'logo' => null,
		];

		if ($data['name'] === '') {
			return Response::json([
				'success' => false,
				'message' => 'Tên thương hiệu không được để trống',
			]);
		}

		// =========================
		// UPLOAD LOGO
		// =========================
		if (!empty($_FILES['logo']['name'])) {
			$file = $_FILES['logo'];

			$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
			$allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

			if (in_array($ext, $allowedExt)) {
				$fileName = uniqid('brand_') . '.' . $ext;

				$uploadDir = PATH_ROOT . '/public/uploads/brands/';

				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}

				$targetPath = $uploadDir . $fileName;

				if (move_uploaded_file($file['tmp_name'], $targetPath)) {
					$data['logo'] = '/uploads/brands/' . $fileName;
				}
			}
		}

		$id = $this->brandRepository->create($data);

		return Response::json([
			'success' => $id > 0,
			'message' => $id ? 'Tạo thương hiệu thành công' : 'Tạo thất bại',
			'id' => $id,
		]);
	}

	// =========================
	// UPDATE
	// =========================
	public function apiUpdate()
	{
		$id = (int) ($_POST['id'] ?? 0);

		if ($id <= 0) {
			return Response::json([
				'success' => false,
				'message' => 'ID không hợp lệ',
			]);
		}

		$data = [
			'name' => trim($_POST['name'] ?? ''),
			'description' => trim($_POST['description'] ?? ''),
			'status' => (int) ($_POST['status'] ?? 1),
		];

		if ($data['name'] === '') {
			return Response::json([
				'success' => false,
				'message' => 'Tên thương hiệu không được để trống',
			]);
		}

		// =========================
		// UPDATE LOGO
		// =========================
		if (!empty($_FILES['logo']['name'])) {
			$file = $_FILES['logo'];

			$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
			$allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

			if (in_array($ext, $allowedExt)) {
				$fileName = uniqid('brand_') . '.' . $ext;

				$uploadDir = PATH_ROOT . '/public/uploads/brands/';

				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}

				$targetPath = $uploadDir . $fileName;

				if (move_uploaded_file($file['tmp_name'], $targetPath)) {
					$data['logo'] = '/uploads/brands/' . $fileName;
				}
			}
		}

		$updated = $this->brandRepository->updateById($id, $data);

		return Response::json([
			'success' => $updated > 0,
			'message' =>
				$updated > 0 ? 'Cập nhật thành công' : 'Không có thay đổi',
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

		$deleted = $this->brandRepository->deleteById($id);

		return Response::json([
			'success' => $deleted > 0,
			'message' =>
				$deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy thương hiệu',
		]);
	}
}
