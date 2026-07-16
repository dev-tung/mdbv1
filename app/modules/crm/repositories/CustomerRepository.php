<?php

class CustomerRepository
{
	protected string $table = 'customers';

	// =========================
	// LIST
	// =========================

	public function getList(
		array $conditions = [],
		int $limit = 0,
		int $offset = 0,
	): array {
		$sql = "
			SELECT *
			FROM {$this->table}
			WHERE 1=1
		";

		$params = [];

		$this->applyFilters($sql, $params, $conditions);

		$sql .= "
			ORDER BY id DESC
		";

		if ($limit > 0) {
			$sql .= " LIMIT {$limit} OFFSET {$offset}";
		}

		return Database::get($sql, $params);
	}

	// =========================
	// CREATE
	// =========================

	public function create(array $data): int
	{
		$fields = [
			'name',
			'group_id',
			'phone',
			'address',
			'description',
			'email',
			'created_at',
			'updated_at',
		];

		$data = array_intersect_key($data, array_flip($fields));

		$data['created_at'] ??= date('Y-m-d H:i:s');
		$data['updated_at'] ??= date('Y-m-d H:i:s');

		$columns = implode(', ', array_keys($data));

		$placeholders = implode(
			', ',
			array_map(fn($key) => ":{$key}", array_keys($data)),
		);

		return Database::insert(
			"
			INSERT INTO {$this->table}
			(
				{$columns}
			)
			VALUES
			(
				{$placeholders}
			)
			",
			$data,
		);
	}

	// =========================
	// UPDATE
	// =========================

	public function updateById(int $id, array $data): int
	{
		$fields = [
			'name',
			'group_id',
			'phone',
			'address',
			'description',
			'email',
			'updated_at',
		];

		$data = array_intersect_key($data, array_flip($fields));

		if (empty($data)) {
			return 0;
		}

		$set = [];

		foreach ($data as $key => $value) {
			$set[] = "{$key} = :{$key}";
		}

		$data['id'] = $id;

		return Database::update(
			"
			UPDATE {$this->table}
			SET
				" .
				implode(', ', $set) .
				"
			WHERE id = :id
			",
			$data,
		);
	}

	// =========================
	// COUNT
	// =========================

	public function count(array $conditions = []): int
	{
		$sql = "
			SELECT COUNT(*) AS total
			FROM {$this->table}
			WHERE 1=1
		";

		$params = [];

		$this->applyFilters($sql, $params, $conditions);

		$row = Database::first($sql, $params);

		return (int) ($row['total'] ?? 0);
	}

	// =========================
	// FILTER BUILDER
	// =========================

	private function applyFilters(
		string &$sql,
		array &$params,
		array $conditions,
	): void {
		$keyword = $conditions['keyword'] ?? null;

		if (is_string($keyword) && trim($keyword) !== '') {
			$keyword = '%' . trim($keyword) . '%';

			$sql .= "
				AND (
					name LIKE :kw1
					OR phone LIKE :kw2
					OR email LIKE :kw3
				)
			";

			$params['kw1'] = $keyword;
			$params['kw2'] = $keyword;
			$params['kw3'] = $keyword;
		}

		if (!empty($conditions['group_id'])) {
			$sql .= "
				AND group_id = :group_id
			";

			$params['group_id'] = (int) $conditions['group_id'];
		}
	}
}
