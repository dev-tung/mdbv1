<?php

class ProductEndpoint
{
	private ProductRepository $productRepository;

	public function __construct()
	{
		$this->productRepository = new ProductRepository();
	}

	// =========================
	// LIST (SERVICE)
	// =========================
	public function apiList()
	{
		$input = request_all();

		$result = $this->productRepository->getList($input);

		return Response::json([
			'success' => true,
			'data' => $result,
		]);
	}

	// =========================
	// SHOW (DIRECT REPOSITORY)
	// =========================
	public function apiShow()
	{
		$id = request_id();

		$data = $this->productRepository->findById($id);

		if (!$data) {
			return Response::json([
				'success' => false,
				'message' => 'Product not found',
			]);
		}

		return Response::json([
			'success' => true,
			'data' => $data,
		]);
	}

	// =========================
	// CREATE (SERVICE + VALIDATION)
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

		$id = $this->productRepository->create(
			$input,
			$_FILES['thumbnail'] ?? [],
		);

		return Response::json([
			'success' => true,
			'message' => 'Create success',
			'id' => $id,
		]);
	}

	// =========================
	// UPDATE (SERVICE + VALIDATION)
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

		$this->productRepository->update(
			(int) ($input['id'] ?? 0),
			$input,
			$_FILES['thumbnail'] ?? [],
		);

		return Response::json([
			'success' => true,
			'message' => 'Update success',
		]);
	}

	// =========================
	// DELETE (SERVICE)
	// =========================
	public function apiDelete()
	{
		$id = request_id();

		$this->productRepository->delete($id);

		return Response::json([
			'success' => true,
			'message' => 'Delete success',
		]);
	}
}
