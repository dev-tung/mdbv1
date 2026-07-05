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

    // =========================
    // SHOW
    // =========================
    public function show(int $id): array
    {
        return Database::call(
            "CALL sp_purchase_show(
                :id
            )",
            [
                'id' => $id
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

    public function update(array $data): void
    {
        Database::query(
            "CALL sp_purchase_update(?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['id'],
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
    }

    public function payment(int $id, string $payment): int
    {
        $result = Database::first(
            "CALL sp_purchase_payment(:id, :payment)",
            [
                'id' => $id,
                'payment' => $payment,
            ]
        );

        return (int) $result['affected_rows'];
    }
}