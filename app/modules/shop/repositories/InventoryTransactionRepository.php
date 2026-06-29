<?php

class InventoryTransactionRepository extends Repository
{
    protected string $table = 'inventory_transactions';

    private InventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InventoryRepository();
    }

    // =========================
    // CREATE (override nhẹ + giữ logic stock)
    // =========================
    public function create(array $data): int
    {
        $id = parent::create($data);

        // side-effect giữ nguyên
        $this->inventoryRepository->updateStock($data['product_id']);

        return $id;
    }

    // =========================
    // BASE SELECT
    // =========================
    private function baseSelect(): string
    {
        return "
            SELECT *
            FROM {$this->table}
            WHERE 1=1
        ";
    }

    // =========================
    // GET BY REFERENCE
    // =========================
    public function getByReference(string $type, int $id): array
    {
        return Database::get(
            $this->baseSelect() . "
                AND reference_type = :type
                AND reference_id = :id
            ",
            [
                'type' => $type,
                'id'   => $id
            ]
        );
    }

    // =========================
    // DELETE BY REFERENCE
    // =========================
    public function deleteByReference(string $type, int $id): void
    {
        $rows = $this->getByReference($type, $id);

        Database::delete("
            DELETE FROM {$this->table}
            WHERE reference_type = :type
            AND reference_id = :id
        ", [
            'type' => $type,
            'id'   => $id
        ]);

        foreach ($rows as $row) {
            $this->inventoryRepository->updateStock($row['product_id']);
        }
    }
}