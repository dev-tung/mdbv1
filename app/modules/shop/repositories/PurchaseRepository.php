<?php

class PurchaseRepository extends Repository
{
    protected string $table = 'purchases';

    /* =================================================
       LIST
    ================================================= */

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

    /* =================================================
       SHOW
    ================================================= */

    public function show(int $id): array
    {
        return Database::call(
            "CALL sp_purchase_show(:id)",
            [
                'id' => $id
            ]
        );
    }

    /* =================================================
       CREATE
    ================================================= */

    public function create(array $data): int
    {
        Database::query(
            "CALL sp_purchase_create(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )",
            [

                $data['supplier_id'],
                $data['warehouse_id'],

                $data['description'],
                $data['note'],

                $data['status'],
                $data['payment'],

                $data['subtotal_amount'],
                $data['vat_rate'],
                $data['vat_amount'],
                $data['total_amount'],

                $data['paid_amount'],
                $data['debt_amount'],

                $data['created_by'],

                json_encode($data['items'])

            ]
        );

        return Database::lastInsertId();
    }

    /* =================================================
       UPDATE
    ================================================= */

    public function update(array $data): void
    {
        Database::query(
            "CALL sp_purchase_update(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )",
            [

                $data['id'],

                $data['supplier_id'],
                $data['warehouse_id'],

                $data['description'],
                $data['note'],

                $data['status'],
                $data['payment'],

                $data['subtotal_amount'],
                $data['vat_rate'],
                $data['vat_amount'],
                $data['total_amount'],

                $data['paid_amount'],
                $data['debt_amount'],

                json_encode($data['items'])

            ]
        );
    }

    /* =================================================
       PAYMENT
    ================================================= */

    public function payment(int $id, string $payment): int
    {
        $result = Database::first(
            "CALL sp_purchase_payment(
                :id,
                :payment
            )",
            [
                'id'      => $id,
                'payment' => $payment
            ]
        );

        return (int) ($result['affected_rows'] ?? 0);
    }

    /* =================================================
    DELETE
    ================================================= */

    public function delete(int $id): void
    {
        Database::query(
            "CALL sp_purchase_delete(?)",
            [
                $id
            ]
        );
    }
}