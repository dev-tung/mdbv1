<?php

class PurchaseRepository extends Repository
{
    protected string $table = 'purchases';

    // =========================
    // LIST
    // =========================
    public function getList(array $filters = []): array
    {
        return Database::get(
            "CALL sp_purchase_list(:keyword, :supplier_id, :warehouse_id)",
            [
                'keyword'      => $filters['keyword'] ?? null,
                'supplier_id'  => $filters['supplier_id'] ?? null,
                'warehouse_id' => $filters['warehouse_id'] ?? null,
            ]
        );
    }

    // =========================
    // DETAIL
    // =========================
    public function findById(int $id): ?array
    {
        return parent::findById($id);
    }

    // =========================
    // CREATE PURCHASE
    // =========================
    public function create(array $data): void
    {
        Database::query(
            "CALL sp_purchase_create(?, ?, ?)",
            [
                $data['supplier_id'],
                $data['warehouse_id'],
                json_encode($data['items'])
            ]
        );
    }

    // =========================
    // UPDATE
    // =========================
    public function updateById(int $id, array $data): int
    {
        return parent::updateById($id, $data);
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return parent::deleteById($id);
    }
}