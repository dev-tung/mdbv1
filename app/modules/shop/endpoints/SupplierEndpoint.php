<?php

class SupplierEndpoint
{
	private readonly SupplierRepository $supplierRepository;

	public function __construct()
	{
		$this->supplierRepository = new SupplierRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->supplierRepository->getList(request_all());

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
		$supplier = $this->supplierRepository->findById(request_id());

		if (!$supplier) {
			return Response::json([
				'success' => false,
				'message' => 'Supplier not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $supplier,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();

		$error = SupplierValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$id = $this->supplierRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Thêm nhà cung cấp thành công!',
			'id' => $id,
			'redirect' => '/admin/suppliers',
		]);
	}

	// =========================
	// UPDATE
	// =========================

	public function apiUpdate()
	{
		$input = request_all();

		$error = SupplierValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$this->supplierRepository->update((int) ($input['id'] ?? 0), $input);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật nhà cung cấp thành công!',
			'redirect' => '/admin/suppliers',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$this->supplierRepository->delete(request_id());

		return Response::json([
			'success' => true,
			'message' => 'Xóa nhà cung cấp thành công!',
		]);
	}
}
