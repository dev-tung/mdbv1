<?php

class OrderItemRepository extends Repository
{
    protected string $table = 'order_items';

    // =========================
    // GET BY ORDER ID
    // =========================
    public function getByOrderId(int $orderId): array
    {
        return Database::get("
            SELECT
                pi.*,
                p.name AS product_name
            FROM {$this->table} pi
            LEFT JOIN products p
                ON p.id = pi.product_id
            WHERE pi.order_id = :order_id
            ORDER BY pi.id ASC
        ", [
            'order_id' => $orderId
        ]);
    }

    // =========================
    // DELETE BY ORDER ID
    // =========================
    public function deleteByOrderId(int $orderId)
    {
        return Database::query("
            DELETE FROM {$this->table}
            WHERE order_id = :order_id
        ", [
            'order_id' => $orderId
        ]);
    }
}