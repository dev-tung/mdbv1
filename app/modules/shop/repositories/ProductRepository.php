<?php

class ProductRepository extends Repository
{
	protected string $table = 'products';

	private const UPLOAD_PATH = PATH_PUBLIC . '/uploads/products';

	/* =================================================
	   BASE SELECT
	================================================= */

	private function baseSelect(): string
	{
		return '
			SELECT
				p.*,
				c.name AS category_name
			FROM products p
			LEFT JOIN categories c
				ON c.id = p.category_id
			WHERE 1 = 1
		';
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

		return upload_file(
			$thumbnail,
			self::UPLOAD_PATH,
		);
	}

	/* =================================================
	   APPLY FILTERS
	================================================= */

	private function applyFilters(string &$sql, array &$params, array $conditions): void
	{
		if (!empty($conditions['keyword'])) {
			$sql .= '
				AND p.name LIKE :keyword
			';

			$params['keyword'] = '%' . $conditions['keyword'] . '%';
		}

		if (!empty($conditions['category_id'])) {
			$sql .= '
				AND p.category_id = :category_id
			';

			$params['category_id'] = $conditions['category_id'];
		}

		if (isset($conditions['status']) && $conditions['status'] !== '') {
			$sql .= '
				AND p.status = :status
			';

			$params['status'] = $conditions['status'];
		}

		if (!empty($conditions['date_from'])) {
			$sql .= '
				AND DATE(p.created_at) >= :date_from
			';

			$params['date_from'] = $conditions['date_from'];
		}

		if (!empty($conditions['date_to'])) {
			$sql .= '
				AND DATE(p.created_at) <= :date_to
			';

			$params['date_to'] = $conditions['date_to'];
		}

		if (!empty($conditions['price_min'])) {
			$sql .= '
				AND p.price >= :price_min
			';

			$params['price_min'] = $conditions['price_min'];
		}

		if (!empty($conditions['price_max'])) {
			$sql .= '
				AND p.price <= :price_max
			';

			$params['price_max'] = $conditions['price_max'];
		}

		if (!empty($conditions['brands']) && is_array($conditions['brands'])) {
			$placeholders = [];

			foreach ($conditions['brands'] as $index => $brandId) {
				$key = ":brand_$index";

				$placeholders[] = $key;

				$params["brand_$index"] = $brandId;
			}

			$sql .= '
				AND p.brand_id IN (
					' . implode(',', $placeholders) . '
				)
			';
		}
	}

	/* =================================================
	   LIST
	================================================= */

	public function getList(array $conditions = []): array
	{
		$page = (int) ($conditions['page'] ?? 1);

		$limit = (int) ($conditions['limit'] ?? 10);

		unset($conditions['page'], $conditions['limit']);

		$offset = ($page - 1) * $limit;

		$sql = $this->baseSelect();

		$params = [];

		$this->applyFilters($sql, $params, $conditions);

		$sql .= '
			ORDER BY p.id DESC
		';

		$sql .= "
			LIMIT {$limit}
			OFFSET {$offset}
		";

		$products = Database::get($sql, $params);

		return [
			$products,
			[
				'total' => $this->count($conditions),
			],
		];
	}

	/* =================================================
	   CREATE
	================================================= */

	public function create(
		array $data,
		array $thumbnail = [],
	): int {
		$data['thumbnail'] = $this->uploadThumbnail($thumbnail);

		return parent::create(
			$this->buildData($data),
		);
	}

	/* =================================================
	   UPDATE
	================================================= */

	public function update(
		int $id,
		array $data,
		array $thumbnail = [],
	): bool {
		$product = parent::findById($id);

		if (!$product) {
			return false;
		}

		$oldThumbnail = $product['thumbnail'];

		$data['thumbnail'] = $this->uploadThumbnail($thumbnail) ?? $oldThumbnail;

		$result = parent::updateById(
			$id,
			$this->buildData($data),
		);

		if (
			$result > 0 &&
			!empty($thumbnail['name']) &&
			!empty($oldThumbnail)
		) {
			delete_file(
				self::UPLOAD_PATH,
				$oldThumbnail,
			);
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

		if (
			$result > 0 &&
			!empty($product['thumbnail'])
		) {
			delete_file(
				self::UPLOAD_PATH,
				$product['thumbnail'],
			);
		}

		return $result > 0;
	}

	/* =================================================
	   COUNT
	================================================= */

	public function count(array $conditions = []): int
	{
		unset($conditions['page'], $conditions['limit']);

		$sql = '
			SELECT COUNT(*) AS total
			FROM products p
			WHERE 1 = 1
		';

		$params = [];

		$this->applyFilters($sql, $params, $conditions);

		$row = Database::first($sql, $params);

		return (int) ($row['total'] ?? 0);
	}
}