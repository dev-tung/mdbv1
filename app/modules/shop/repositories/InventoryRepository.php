<?php

class InventoryRepository extends Repository
{
    // =========================
    // LIST
    // =========================
    public function getList(array $filters = []): array
    {
        return Database::get(
            'CALL sp_inventory_list(:keyword)',
            [
                'keyword' => $filters['keyword'] ?? null,
            ],
        );
    }

    // =========================
    // STOCK
    // =========================
    public function getStock(array $filters = []): array
    {
        return Database::get(
            'CALL sp_inventory_stock(:keyword)',
            [
                'keyword' => $filters['keyword'] ?? null,
            ],
        );
    }
}
