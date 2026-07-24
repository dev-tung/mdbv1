<?php
namespace App\Shop\Endpoints;

use App\Core\Auth;
use App\Core\Response;
use App\Shop\Repositories\PurchaseRepository;
use App\Shop\Validators\PurchaseValidator;
use Throwable;

class PurchaseEndpoint
{
	private PurchaseRepository $purchaseRepository;

	public function __construct()
	{
		$this->purchaseRepository = new PurchaseRepository();
	}

	// =========================
	// LIST
	// =========================
	public function apiList()
	{
		$filters = request_all();

		$result = $this->purchaseRepository->getList($filters);

		return Response::json([
			'success' => true,
			'message' => 'Lấy danh sách phiếu nhập thành công',
			'data' => $result,
		]);
	}

	// =========================
	// SHOW
	// =========================
	public function apiShow()
	{
		$id = request_id();

		$data = $this->purchaseRepository->show($id);

		if (!$data) {
			return Response::json([
				'success' => false,

				'message' => 'Không tìm thấy phiếu nhập',
				'data' => null,
			]);
		}

		return Response::json([
			'success' => true,
			'message' => 'Lấy chi tiết phiếu nhập thành công',
			'data' => $data,
		]);
	}

	// =========================
	// CREATE
	// =========================
	public function apiCreate()
	{
		$input = request_all();
		$error = PurchaseValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$input['created_by'] = Auth::id();
		$id = $this->purchaseRepository->create($input);

		return Response::json([
			'success' => true,
			'message' => 'Tạo phiếu nhập thành công',
			'data' => [
				'id' => $id,
			],
			'redirect' => '/admin/purchases',
		]);
	}

	// =========================
	// UPDATE
	// =========================
	public function apiUpdate()
	{
		$input = request_all();

		$error = PurchaseValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$input['updated_by'] = Auth::id();
		$this->purchaseRepository->update($input);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật phiếu nhập thành công',
			'redirect' => '/admin/purchases',
		]);
	}

	// =========================
	// DELETE
	// =========================
	public function apiDelete()
	{
		$id = request_id();

		$this->purchaseRepository->delete($id);

		return Response::json([
			'success' => true,
			'message' => 'Xoá phiếu nhập thành công',
		]);
	}

	// =========================
	// STATUS
	// =========================
	public function apiStatus()
	{
		try {
			$input = request_all();

			if (empty($input['id']) || !isset($input['status'])) {
				return Response::json([
					'success' => false,
					'message' => 'Thiếu dữ liệu trạng thái',
				]);
			}

			$updated = $this->purchaseRepository->status((int) $input['id'], $input['status']);

			return Response::json([
				'success' => true,
				'message' => 'Cập nhật trạng thái thành công',
				'data' => [
					'affected_rows' => $updated,
				],
			]);
		} catch (Throwable $e) {
			return Response::json(
				[
					'success' => false,
					'message' => $e->getMessage(),
				],
				400,
			);
		}
	}

	// =========================
	// PAYMENT
	// =========================
	public function apiPayment()
	{
		$input = request_all();

		if (empty($input['id']) || !isset($input['payment'])) {
			return Response::json([
				'success' => false,

				'message' => 'Thiếu dữ liệu thanh toán',
			]);
		}

		$updated = $this->purchaseRepository->payment((int) $input['id'], $input['payment']);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật thanh toán thành công',
			'data' => [
				'affected_rows' => $updated,
			],
		]);
	}
}
