<?php

class ProductRepository extends Repository
{
	protected string $table = 'products';

	private const UPLOAD_PATH = PATH_PUBLIC . '/uploads/products';
	private const UPLOAD_URL = '/uploads/products';

	/* =================================================
       LIST
    ================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_product_list(?, ?, ?, ?, ?, ?, ?, ?, ?)',
			array_params(
				[
					'keyword',
					'category_id',
					'status',
					'date_from',
					'date_to',
					'price_min',
					'price_max',
					'page',
					'per_page',
				],
				$filters,
			),
		);
	}

	/* =================================================
       BUILD DATA
    ================================================= */

	private function buildData(array $data): array
	{
		return [
			'category_id' => $data['category_id'],
			'brand_id' => $data['brand_id'],
			'name' => $data['name'],
			'thumbnail' => $data['thumbnail'] ?? null,
			'price' => $data['price'],
			'sale_price' => $data['sale_price'],
			'status' => $data['status'],
			'description' => $data['description'],
		];
	}

	/* =================================================
       UPLOAD
    ================================================= */

	private function uploadThumbnail(array $thumbnail): ?string
	{
		if (empty($thumbnail['name'])) {
			return null;
		}

		$fileName = upload_file($thumbnail, self::UPLOAD_PATH);

		return self::UPLOAD_URL . '/' . $fileName;
	}

	/* =================================================
       CREATE
    ================================================= */

	public function create(array $data, array $thumbnail = []): int
	{
		$data['thumbnail'] = $this->uploadThumbnail($thumbnail);

		return parent::create($this->buildData($data));
	}

	/* =================================================
       UPDATE
    ================================================= */

	public function update(int $id, array $data, array $thumbnail = []): bool
	{
		$product = parent::findById($id);

		if (!$product) {
			return false;
		}

		$oldThumbnail = $product['thumbnail'];

		$data['thumbnail'] = $this->uploadThumbnail($thumbnail) ?? $oldThumbnail;

		$result = parent::updateById($id, $this->buildData($data));

		if ($result > 0 && !empty($thumbnail['name']) && !empty($oldThumbnail)) {
			delete_file(self::UPLOAD_PATH, basename($oldThumbnail));
		}

		return $result > 0;
	}

	/* =================================================
       DELETE
    ================================================= */

	public function delete(int $id): bool
	{
		$product = parent::findById($id);

		if (!$product) {
			return false;
		}

		$result = parent::deleteById($id);

		if ($result > 0 && !empty($product['thumbnail'])) {
			delete_file(self::UPLOAD_PATH, basename($product['thumbnail']));
		}

		return $result > 0;
	}
}
