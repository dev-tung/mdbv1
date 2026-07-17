<?php

class WarehouseRepository
{
	/**
	 * BUILD WHERE
	 */
	private function buildWhere(array $conditions, array &$params): string
	{
		$sql = ' WHERE 1=1';

		// KEYWORD
		if (!empty($conditions['keyword'])) {
			$sql .= '
                AND (
                    name LIKE :keyword
                    OR address LIKE :keyword
                )
            ';

			$params['keyword'] = '%' . trim($conditions['keyword']) . '%';
		}

		// STATUS
		if (isset($conditions['status']) && $conditions['status'] !== '') {
			$sql .= ' AND status = :status';
			$params['status'] = $conditions['status'];
		}

		return $sql;
	}

	/**
	 * GET LIST
	 */
	public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
	{
		$params = [];

		$sql = '
            SELECT *
            FROM warehouses
        ';

		$sql .= $this->buildWhere($conditions, $params);

		$sql .= ' ORDER BY id DESC';

		if ($limit > 0) {
			$limit = (int) $limit;
			$offset = (int) $offset;

			$sql .= " LIMIT {$limit} OFFSET {$offset}";
		}

		return Database::get($sql, $params);
	}

	/**
	 * COUNT
	 */
	public function count(array $conditions = []): int
	{
		$params = [];

		$sql = '
            SELECT COUNT(*) AS total
            FROM warehouses
        ';

		$sql .= $this->buildWhere($conditions, $params);

		$row = Database::first($sql, $params);

		return (int) ($row['total'] ?? 0);
	}

	/**
	 * FIND BY ID
	 */
	public function findById(int $id): ?array
	{
		return Database::first(
			'
                SELECT *
                FROM warehouses
                WHERE id = :id
                LIMIT 1
            ',
			[
				'id' => $id,
			],
		);
	}

	/**
	 * CREATE
	 */
	public function create(array $data): int
	{
		$fields = array_keys($data);

		$columns = implode(', ', $fields);
		$placeholders = ':' . implode(', :', $fields);

		$sql = "
            INSERT INTO warehouses ({$columns})
            VALUES ({$placeholders})
        ";

		return Database::insert($sql, $data);
	}

	/**
	 * UPDATE
	 */
	public function updateById(int $id, array $data): int
	{
		if (empty($data)) {
			return 0;
		}

		$set = [];

		foreach ($data as $key => $value) {
			$set[] = "{$key} = :{$key}";
		}

		$data['id'] = $id;

		$sql =
			'
            UPDATE warehouses
            SET ' .
			implode(', ', $set) .
			'
            WHERE id = :id
        ';

		return Database::update($sql, $data);
	}

	/**
	 * DELETE
	 */
	public function deleteById(int $id): int
	{
		return Database::delete(
			'
                DELETE FROM warehouses
                WHERE id = :id
            ',
			[
				'id' => $id,
			],
		);
	}
}
