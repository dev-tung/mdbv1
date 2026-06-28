<?php

class InventoryTransactionModel
{
    /**
     * Tạo lịch sử biến động kho
     */
    public function create(array $data)
    {
        $id = DB::insert("
            INSERT INTO inventory_transactions
            (
                product_id,
                type,
                quantity,
                reference_type,
                reference_id,
                note
            )
            VALUES (?,?,?,?,?,?)
        ", [
            $data['product_id'],
            $data['type'],
            $data['quantity'],
            $data['reference_type'] ?? null,
            $data['reference_id'] ?? null,
            $data['note'] ?? null
        ]);

        (new InventoryModel())->updateStock($data['product_id']);

        return $id;
    }

    /**
     * Xóa theo chứng từ
     */
    public function deleteByReference(string $type, int $id)
    {
        $rows = $this->getByReference($type, $id);

        DB::delete("
            DELETE FROM inventory_transactions
            WHERE reference_type = ?
            AND reference_id = ?
        ", [$type, $id]);

        $inventory = new InventoryModel();

        foreach ($rows as $row) {
            $inventory->updateStock($row['product_id']);
        }
    }

    /**
     * Lấy giao dịch theo chứng từ
     */
    public function getByReference(string $type, int $id)
    {
        return DB::select("
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