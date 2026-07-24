<?php
namespace App\Shop\Endpoints;

use App\Core\Response;
use App\Shop\Repositories\WarehouseRepository;
use App\Shop\Validators\WarehouseValidator;

class WarehouseEndpoint
{
	private readonly WarehouseRepository $warehouseRepository;

	public function __construct()
	{
		$this->warehouseRepository = new WarehouseRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->warehouseRepository->getList(request_all());

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
		$warehouse = $this->warehouseRepository->findById(request_id());

		if (!$warehouse) {
			return Response::json([
				'success' => false,
				'message' => 'Warehouse not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $warehouse,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();

		$error = WarehouseValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$id = $this->warehouseRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Thêm kho thành công!',
			'id' => $id,
			'redirect' => '/admin/warehouses',
		]);
	}

	// =========================
	// UPDATE
	// =========================

	public function apiUpdate()
	{
		$input = request_all();

		$error = WarehouseValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$this->warehouseRepository->update((int) ($input['id'] ?? 0), $input);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật kho thành công!',
			'redirect' => '/admin/warehouses',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$this->warehouseRepository->delete(request_id());

		return Response::json([
			'success' => true,
			'message' => 'Xóa kho thành công!',
		]);
	}
}
