<?php

class SupplierRepository
{
	protected string $table = 'suppliers';

	/**
	 * Lấy danh sách supplier
	 */
	public function getList(array $conditions = []): array
	{
		$sql = "SELECT * FROM {$this->table} WHERE 1=1";
		$params = [];

		// KEYWORD SEARCH
		if (!empty($conditions['keyword'])) {
			$sql .= ' AND (
                name LIKE :keyword
            )';
			$params['keyword'] = '%' . $conditions['keyword'] . '%';
		}

		$sql .= ' ORDER BY id DESC';

		return Database::get($sql, $params);
	}

	/**
	 * Đếm supplier (pagination)
	 */
	public function count(array $conditions = []): int
	{
		$sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
		$params = [];

		if (!empty($conditions['keyword'])) {
			$sql .= ' AND (
                name LIKE :keyword
                OR phone LIKE :keyword
                OR email LIKE :keyword
            )';

			$params['keyword'] = '%' . $conditions['keyword'] . '%';
		}

		$row = Database::first($sql, $params);

		return (int) ($row['total'] ?? 0);
	}
}
