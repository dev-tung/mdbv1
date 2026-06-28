<?php

class PurchaseModel
{
    protected string $table = 'purchases';

    // =========================
    // LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT 
                    p.*,
                    s.name AS supplier_name,
                    w.name AS warehouse_name
                FROM {$this->table} p
                LEFT JOIN suppliers s ON s.id = p.supplier_id
                LEFT JOIN warehouses w ON w.id = p.warehouse_id
                WHERE 1=1";

        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND p.code LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['supplier_id'])) {
            $sql .= " AND p.supplier_id = :supplier_id";
            $params['supplier_id'] = $conditions['supplier_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND p.payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        $sql .= " ORDER BY p.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    // =========================
    // FIND BY ID
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT t.*, s.name as supplier_name
            FROM {$this->table} t
            LEFT JOIN suppliers s ON s.id = t.supplier_id
            WHERE t.id = :id
            LIMIT 1",
            ['id' => $id]
        );
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns})
                VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    // =========================
    // UPDATE BY ID
    // =========================
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

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table}
             WHERE id = :id",
            ['id' => $id]
        );
    }

    // =========================
    // COUNT (pagination)
    // =========================
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table}
                WHERE 1=1";

        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND code LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['supplier_id'])) {
            $sql .= " AND supplier_id = :supplier_id";
            $params['supplier_id'] = $conditions['supplier_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        $row = Database::first($sql, $params);

        return (int)($row['total'] ?? 0);
    }
}