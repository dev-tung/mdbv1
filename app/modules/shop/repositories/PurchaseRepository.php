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
    // DETAIL
    // =========================
    public function findById(int $id): ?array
    {
        return parent::findById($id);
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

    // =========================
    // UPDATE
    // =========================
    public function updateById(int $id, array $data): int
    {
        return parent::updateById($id, $data);
    }

    public function updatePayment(int $id, string $payment): int
    {

        return Database::query(
            "UPDATE purchases
            SET
                payment = :payment,
                paid_amount = CASE
                    WHEN :payment_paid = 'paid' THEN total_amount
                    WHEN :payment_unpaid = 'unpaid' THEN 0
                    ELSE paid_amount
                END,
                debt_amount = CASE
                    WHEN :payment_paid2 = 'paid' THEN 0
                    WHEN :payment_unpaid2 = 'unpaid' THEN total_amount
                    ELSE debt_amount
                END
            WHERE id = :id",
            [
                'id' => $id,
                'payment' => $payment,
                'payment_paid' => $payment,
                'payment_unpaid' => $payment,
                'payment_paid2' => $payment,
                'payment_unpaid2' => $payment,
            ]
        )->rowCount();
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return parent::deleteById($id);
    }
}