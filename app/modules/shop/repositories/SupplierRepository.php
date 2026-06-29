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
            $sql .= " AND (
                name LIKE :keyword
            )";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $sql .= " ORDER BY id DESC";

        return Database::get($sql, $params);
    }

    /**
     * Lấy 1 supplier theo ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * Thêm supplier
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns})
                VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    /**
     * Cập nhật supplier
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table}
                SET " . implode(', ', $set) . "
                WHERE id = :id";

        return Database::update($sql, $data);
    }

    /**
     * Xoá supplier
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Đếm supplier (pagination)
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND (
                name LIKE :keyword
                OR phone LIKE :keyword
                OR email LIKE :keyword
            )";

            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}