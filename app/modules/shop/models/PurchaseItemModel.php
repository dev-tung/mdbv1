<?php

class PurchaseItemModel
{
    protected string $table = 'purchase_items';

    // =========================
    // GET BY PURCHASE ID
    // =========================
    public function getByPurchaseId(int $purchaseId): array
    {
        return Database::get(
            "SELECT
                pi.*,
                p.name AS product_name
            FROM {$this->table} pi
            LEFT JOIN products p
                ON p.id = pi.product_id
            WHERE pi.purchase_id = :purchase_id
            ORDER BY pi.id ASC",
            ['purchase_id' => $purchaseId]
        );
    }

    // =========================
    // FIND ONE
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT *
             FROM {$this->table}
             WHERE id = :id
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
    // DELETE BY PURCHASE ID
    // =========================
    public function deleteByPurchaseId(int $purchaseId): int
    {
        return Database::delete(
            "DELETE FROM {$this->table}
             WHERE purchase_id = :purchase_id",
            ['purchase_id' => $purchaseId]
        );
    }

    // =========================
    // DELETE ONE
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
    // UPDATE (optional dùng sau)
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
}