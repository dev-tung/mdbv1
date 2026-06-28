<?php

class CategoryModel
{
    protected string $table = 'categories';

    /**
     * Lấy tất cả danh mục
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // KEYWORD SEARCH (nếu cần sau này)
        if (!empty($conditions['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $sql .= " ORDER BY id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    /**
     * Lấy 1 category theo ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * Thêm category
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    /**
     * Cập nhật category theo ID
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";

        return Database::update($sql, $data);
    }

    /**
     * Xoá category theo ID
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Đếm category (nếu cần pagination sau này)
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}