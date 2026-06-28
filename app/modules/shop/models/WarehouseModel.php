<?php

class WarehouseModel
{
    protected string $table = 'warehouses';

    /**
     * Lấy danh sách kho
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // SEARCH KEYWORD
        if (!empty($conditions['keyword'])) {
            $sql .= " AND (
                name LIKE :keyword
                OR address LIKE :keyword
            )";

            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        // STATUS FILTER
        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        $sql .= " ORDER BY id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    /**
     * Lấy 1 warehouse theo ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * Thêm warehouse
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
     * Cập nhật warehouse
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
     * Xoá warehouse
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Đếm warehouse (pagination)
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND (
                name LIKE :keyword
                OR address LIKE :keyword
            )";

            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}