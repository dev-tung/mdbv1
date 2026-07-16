<?php

abstract class Repository
{
	protected string $table;

	// =========================
	// CREATE
	// =========================
	public function create(array $data): int
	{
		if (empty($data)) {
			throw new InvalidArgumentException('Data cannot be empty');
		}

		$fields = array_keys($data);

		$columns = implode(', ', $fields);
		$placeholders = ':' . implode(', :', $fields);

		$sql = "
            INSERT INTO {$this->table} ({$columns})
            VALUES ({$placeholders})
        ";

		Database::query($sql, $data);

		return (int) Database::pdo()->lastInsertId();
	}

	// =========================
	// CREATE BATCH
	// =========================
	public function createBatch(array $rows): int
	{
		if (empty($rows)) {
			return 0;
		}

		$columns = array_keys($rows[0]);

		$params = [];
		$placeholders = [];

		foreach ($rows as $index => $row) {
			$values = [];

			foreach ($columns as $column) {
				$key = "{$column}_{$index}";

				$values[] = ":{$key}";
				$params[$key] = $row[$column] ?? null;
			}

			$placeholders[] = '(' . implode(', ', $values) . ')';
		}

		$sql = sprintf(
			'INSERT INTO %s (%s) VALUES %s',
			$this->table,
			implode(', ', $columns),
			implode(', ', $placeholders),
		);

		return Database::query($sql, $params)->rowCount();
	}

	// =========================
	// UPDATE BY ID
	// =========================
	public function updateById(int $id, array $data): int
	{
		if (empty($data)) {
			return 0;
		}

		$set = [];

		foreach ($data as $field => $value) {
			$set[] = "{$field} = :{$field}";
		}

		$data['id'] = $id;

		$sql =
			"
            UPDATE {$this->table}
            SET " .
			implode(', ', $set) .
			'
            WHERE id = :id
        ';

		return Database::query($sql, $data)->rowCount();
	}

	// =========================
	// DELETE BY ID
	// =========================
	public function deleteById(int $id): int
	{
		return Database::query("DELETE FROM {$this->table} WHERE id = :id", [
			'id' => $id,
		])->rowCount();
	}

	// =========================
	// FIND BY ID
	// =========================
	public function findById(int $id): ?array
	{
		return Database::first("SELECT * FROM {$this->table} WHERE id = :id", [
			'id' => $id,
		]);
	}
}
