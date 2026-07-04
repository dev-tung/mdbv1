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
            "CALL sp_purchase_list(
                :date_from,
                :date_to,
                :supplier_id,
                :payment,
                :page,
                :per_page
            )",
            [
                'date_from'   => $filters['date_from'] ?: null,
                'date_to'     => $filters['date_to'] ?: null,
                'supplier_id' => $filters['supplier_id'] ?: null,
                'payment'     => $filters['payment'] ?: null,
                'page'        => $filters['page'] ?? 1,
                'per_page'    => $filters['per_page'] ?? 20,
            ]
        );
    }

    public function create(array $data): int
    {
        Database::query(
            "CALL sp_purchase_create(?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['supplier_id'],
                $data['warehouse_id'],
                $data['description'],
                $data['status'],
                $data['payment'],
                $data['paid_amount'],
                $data['debt_amount'],
                json_encode($data['items'])
            ]
        );

        return Database::lastInsertId();
    }

    public function updatePayment(int $id, string $payment): int
    {
        $result = Database::first(
            "CALL sp_purchase_update_payment(:id, :payment)",
            [
                'id' => $id,
                'payment' => $payment,
            ]
        );

        return (int) $result['affected_rows'];
    }
}