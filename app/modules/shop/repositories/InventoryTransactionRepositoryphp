<?php

class InventoryTransactionRepository
{
    /**
     * Tạo lịch sử biến động kho
     */
    public function create(array $data)
    {
        $id = Database::insert("
            INSERT INTO inventory_transactions
            (
                product_id,
                type,
                quantity,
                reference_type,
                reference_id,
                note,
                warehouse_id
            )
            VALUES (?,?,?,?,?,?,?)
        ", [
            $data['product_id'],
            $data['type'],
            $data['quantity'],
            $data['reference_type'] ?? null,
            $data['reference_id'] ?? null,
            $data['note'] ?? null,
            $data['warehouse_id'] ?? null
        ]);

        (new InventoryRepository())->updateStock($data['product_id']);

        return $id;
    }

    /**
     * Xóa theo chứng từ
     */
    public function deleteByReference(string $type, int $id)
    {
        $rows = $this->getByReference($type, $id);

        Database::delete("
            DELETE FROM inventory_transactions
            WHERE reference_type = ?
            AND reference_id = ?
        ", [$type, $id]);

        $inventory = new InventoryRepository();

        foreach ($rows as $row) {
            $inventory->updateStock($row['product_id']);
        }
    }

    /**
     * Lấy giao dịch theo chứng từ
     */
    public function getByReference(string $type, int $id)
    {
        return Database::get("
            SELECT *
            FROM inventory_transactions
            WHERE reference_type = ?
            AND reference_id = ?
        ", [
            $type,
            $id
        ]);
    }
}