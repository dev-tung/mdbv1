<?php

class OrderItemModel
{
    protected string $table = 'order_items';

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
    // GET BY ORDER ID
    // =========================
    public function getByOrderId(int $orderId): array
    {
        return Database::get(
            "SELECT 
                oi.*,
                p.name AS product_name
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :order_id",
            ['order_id' => $orderId]
        );
    }

    // =========================
    // DELETE BY ORDER ID
    // =========================
    public function deleteByOrderId(int $orderId): int
    {
        return Database::delete(
            "DELETE FROM {$this->table}
             WHERE order_id = :order_id",
            ['order_id' => $orderId]
        );
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
}