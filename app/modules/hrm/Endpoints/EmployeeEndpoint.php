<?php

namespace App\HRM\Endpoints;

use App\Core\Response;
use App\HRM\Repositories\EmployeeRepository;
use App\HRM\Validators\EmployeeValidator;

class EmployeeEndpoint
{
	private readonly EmployeeRepository $employeeRepository;

	public function __construct()
	{
		$this->employeeRepository = new EmployeeRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->employeeRepository->getList(request_all());

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
		$employee = $this->employeeRepository->findById(request_id());

		if (!$employee) {
			return Response::json([
				'success' => false,
				'message' => 'Employee not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $employee,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();
		$error = EmployeeValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$id = $this->employeeRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Thêm khách hàng thành công!',
			'id' => $id,
			'redirect' => '/admin/employees',
		]);
	}

	// =========================
	// UPDATE
	// =========================

	public function apiUpdate()
	{
		$input = request_all();

		$error = EmployeeValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$this->employeeRepository->update((int) ($input['id'] ?? 0), $input);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật khách hàng thành công!',
			'redirect' => '/admin/employees',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$this->employeeRepository->delete(request_id());

		return Response::json([
			'success' => true,
			'message' => 'Xóa khách hàng thành công!',
		]);
	}
}
