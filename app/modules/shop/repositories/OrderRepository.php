<?php

class OrderRepository extends Repository
{
	protected string $table = 'orders';

	/* =================================================
       LIST
    ================================================= */

	public function getList(array $filters = []): array
	{
		$sql = '
            CALL sp_order_list(
                :date_from,
                :date_to,
                :customer,
                :payment,
                :status
            )
        ';

		$params = [
			'date_from' => $filters['date_from'] ?? null,
			'date_to' => $filters['date_to'] ?? null,
			'customer' => $filters['customer'] ?? null,
			'payment' => $filters['payment'] ?? null,
			'status' => $filters['status'] ?? null,
		];

		return Database::get($sql, $params);
	}

	/* =================================================
       SHOW
    ================================================= */

	public function show(int $id): array
	{
		return Database::call('CALL sp_order_show(:id)', [
			'id' => $id,
		]);
	}

	/* =================================================
    CREATE
    ================================================= */

	public function create(array $data): int
	{
		Database::query(
			'CALL sp_order_create(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )',
			[
				// CUSTOMER
				$data['customer_id'] ?? null,

				// DESCRIPTION
				$data['description'] ?? null,

				// NOTE
				$data['note'] ?? null,

				// STATUS
				$data['status'] ?? 'draft',

				// PAYMENT
				$data['payment'] ?? 'unpaid',

				// AMOUNTS
				$data['subtotal_amount'] ?? 0,

				$data['discount_amount'] ?? 0,

				$data['vat_rate'] ?? 0,

				$data['vat_amount'] ?? 0,

				$data['total_amount'] ?? 0,

				$data['paid_amount'] ?? 0,

				$data['debt_amount'] ?? 0,

				// CREATED BY
				$data['created_by'],

				// ITEMS
				json_encode($data['items'] ?? [], JSON_UNESCAPED_UNICODE),
			],
		);

		return Database::lastInsertId();
	}

	/* =================================================
    UPDATE
    ================================================= */

	public function update(array $data): void
	{
		Database::query(
			'CALL sp_order_update(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )',
			[
				// ID
				$data['id'],

				// CUSTOMER
				$data['customer_id'] ?? null,

				// INFO
				$data['description'] ?? null,

				$data['note'] ?? null,

				// STATUS
				$data['status'] ?? 'draft',

				$data['payment'] ?? 'unpaid',

				// AMOUNT
				$data['subtotal_amount'] ?? 0,

				$data['discount_amount'] ?? 0,

				$data['vat_rate'] ?? 0,

				$data['vat_amount'] ?? 0,

				$data['total_amount'] ?? 0,

				$data['paid_amount'] ?? 0,

				$data['debt_amount'] ?? 0,

				// ITEMS
				json_encode($data['items'] ?? [], JSON_UNESCAPED_UNICODE),
			],
		);
	}

	/* =================================================
       PAYMENT
    ================================================= */

	public function payment(int $id, string $payment): int
	{
		$result = Database::first(
			'CALL sp_order_payment(
                :id,
                :payment
            )',
			[
				'id' => $id,

				'payment' => $payment,
			],
		);

		return (int) ($result['affected_rows'] ?? 0);
	}

	/* =================================================
       DELETE
    ================================================= */

	public function delete(int $id): void
	{
		Database::query('CALL sp_order_delete(?)', [$id]);
	}
}
