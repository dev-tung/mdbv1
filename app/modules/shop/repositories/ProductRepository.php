<?php

class ProductRepository extends Repository
{
	protected string $table = 'products';

	// =========================
	// BASE SELECT
	// =========================
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

	// =========================
	// APPLY FILTERS
	// =========================
	private function applyFilters(string &$sql, array &$params, array $conditions): void
	{
		// =========================
		// KEYWORD
		// =========================
		if (!empty($conditions['keyword'])) {
			$sql .= '
				AND p.name LIKE :keyword
			';

			$params['keyword'] = '%' . $conditions['keyword'] . '%';
		}

		// =========================
		// CATEGORY
		// =========================
		if (!empty($conditions['category_id'])) {
			$sql .= '
				AND p.category_id = :category_id
			';

			$params['category_id'] = $conditions['category_id'];
		}

		// =========================
		// STATUS
		// =========================
		if (isset($conditions['status']) && $conditions['status'] !== '') {
			$sql .= '
				AND p.status = :status
			';

			$params['status'] = $conditions['status'];
		}

		// =========================
		// DATE FROM
		// =========================
		if (!empty($conditions['date_from'])) {
			$sql .= '
				AND DATE(p.created_at) >= :date_from
			';

			$params['date_from'] = $conditions['date_from'];
		}

		// =========================
		// DATE TO
		// =========================
		if (!empty($conditions['date_to'])) {
			$sql .= '
				AND DATE(p.created_at) <= :date_to
			';

			$params['date_to'] = $conditions['date_to'];
		}

		// =========================
		// PRICE
		// =========================
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

		// =========================
		// BRAND
		// =========================
		if (!empty($conditions['brands']) && is_array($conditions['brands'])) {
			$placeholders = [];

			foreach ($conditions['brands'] as $index => $brandId) {
				$key = ":brand_$index";

				$placeholders[] = $key;

				$params["brand_$index"] = $brandId;
			}

			$sql .=
				'
				AND p.brand_id IN (
					' .
				implode(',', $placeholders) .
				'
				)
			';
		}
	}

	// =========================
	// LIST
	// =========================
	public function getList(array $conditions = []): array
	{
		$page = (int) ($conditions['page'] ?? 1);

		$limit = (int) ($conditions['limit'] ?? 10);

		unset($conditions['page'], $conditions['limit']);

		$offset = ($page - 1) * $limit;

		// =========================
		// GET PRODUCTS
		// =========================

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

		// =========================
		// GET TOTAL
		// =========================

		$total = $this->count($conditions);

		return [
			$products,

			[
				'total' => $total,
			],
		];
	}

	// =========================
	// CREATE
	// =========================
	public function create(array $data, array $thumbnail = []): int
	{
		if (!empty($thumbnail['name'])) {
			$data['thumbnail'] = upload_file($thumbnail, PATH_PUBLIC . '/uploads/products');
		}

		return parent::create([
			'category_id' => $data['category_id'],

			'brand_id' => $data['brand_id'],

			'name' => $data['name'],

			'thumbnail' => $data['thumbnail'] ?? null,

			'price' => $data['price'],

			'status' => $data['status'],

			'description' => $data['description'],
		]);
	}

	// =========================
	// UPDATE
	// =========================
	public function update(int $id, array $data, array $thumbnail = []): bool
	{
		$product = parent::findById($id);

		if (!$product) {
			return false;
		}

		$oldThumbnail = $product['thumbnail'];

		if (!empty($thumbnail['name'])) {
			$data['thumbnail'] = upload_file($thumbnail, PATH_PUBLIC . '/uploads/products');
		} else {
			$data['thumbnail'] = $oldThumbnail;
		}

		$result = parent::updateById($id, [
			'category_id' => $data['category_id'],

			'brand_id' => $data['brand_id'],

			'name' => $data['name'],

			'thumbnail' => $data['thumbnail'],

			'price' => $data['price'],

			'status' => $data['status'],

			'description' => $data['description'],
		]);

		if ($result > 0 && !empty($thumbnail['name']) && !empty($oldThumbnail)) {
			delete_file(PATH_PUBLIC . '/uploads/products', $oldThumbnail);
		}

		return $result > 0;
	}

	// =========================
	// DELETE
	// =========================
	public function delete(int $id): bool
	{
		$product = parent::findById($id);

		if (!$product) {
			return false;
		}

		$result = parent::deleteById($id);

		if ($result > 0 && !empty($product['thumbnail'])) {
			delete_file(PATH_PUBLIC . '/uploads/products', $product['thumbnail']);
		}

		return $result > 0;
	}

	// =========================
	// COUNT
	// =========================
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
