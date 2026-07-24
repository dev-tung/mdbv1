<?php
namespace App\Shop\Endpoints;

use App\Core\Response;
use App\Shop\Repositories\ProductRepository;
use App\Shop\Validators\ProductValidator;

class ProductEndpoint
{
	private readonly ProductRepository $productRepository;

	public function __construct()
	{
		$this->productRepository = new ProductRepository();
	}

	// =========================
	// LIST
	// =========================

	public function apiList()
	{
		$data = $this->productRepository->getList(request_all());

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
		$product = $this->productRepository->findById(request_id());

		if (!$product) {
			return Response::json([
				'success' => false,
				'message' => 'Product not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $product,
		]);
	}

	// =========================
	// CREATE
	// =========================

	public function apiCreate()
	{
		$input = request_all();

		$error = ProductValidator::create($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$id = $this->productRepository->create($input, $_FILES['thumbnail'] ?? []);

		return Response::json([
			'success' => true,
			'message' => 'Thêm sản phẩm thành công!',
			'id' => $id,
			'redirect' => '/admin/products',
		]);
	}

	// =========================
	// UPDATE
	// =========================

	public function apiUpdate()
	{
		$input = request_all();
		$error = ProductValidator::update($input);

		if ($error) {
			return Response::json([
				'success' => false,
				'message' => $error,
			]);
		}

		$this->productRepository->update((int) ($input['id'] ?? 0), $input, $_FILES['thumbnail'] ?? []);

		return Response::json([
			'success' => true,
			'message' => 'Cập nhật sản phẩm thành công!',
			'redirect' => '/admin/products',
		]);
	}

	// =========================
	// DELETE
	// =========================

	public function apiDelete()
	{
		$this->productRepository->delete(request_id());

		return Response::json([
			'success' => true,
			'message' => 'Xóa sản phẩm thành công!',
		]);
	}
}
