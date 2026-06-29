<?php

class PurchaseItemRepository extends Repository
{
    protected string $table = 'purchase_items';

    // =========================
    // GET BY PURCHASE ID
    // =========================
    public function getByPurchaseId(int $purchaseId): array
    {
        return Database::get("
            SELECT
                pi.*,
                p.name AS product_name
            FROM {$this->table} pi
            LEFT JOIN products p
                ON p.id = pi.product_id
            WHERE pi.purchase_id = :purchase_id
            ORDER BY pi.id ASC
        ", [
            'purchase_id' => $purchaseId
        ]);
    }

    // =========================
    // DELETE BY PURCHASE ID
    // =========================
    public function deleteByPurchaseId(int $purchaseId)
    {
        return Database::query("
            DELETE FROM {$this->table}
            WHERE purchase_id = :purchase_id
        ", [
            'purchase_id' => $purchaseId
        ]);
    }
}