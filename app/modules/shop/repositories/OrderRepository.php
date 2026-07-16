<?php

class OrderRepository extends Repository
{
	protected string $table = 'orders';

	/* =================================================
	   LIST
	================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_order_list(?, ?, ?, ?, ?)',
			array_params(
				[
					'date_from',
					'date_to',
					'customer',
					'payment',
					'status',
				],
				$filters,
			),
		);
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
				?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
			)',
			[
				$data['customer_id'],

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

				json_encode($data['items']),
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
				?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
			)',
			[
					$data['id'],

					$data['customer_id'],

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

					json_encode($data['items']),
			],
		);
	}

	/* =================================================
	   STATUS
	================================================= */

	public function status(int $id, string $status): int
	{
		try {
			$result = Database::first(
				'CALL sp_order_status(
						:id,
						:status
					)',
				[
					'id' => $id,

					'status' => $status,
				],
			);
		} catch (PDOException $e) {
			throw new Exception(
				$e->errorInfo[2] ?? $e->getMessage(),

				(int) ($e->errorInfo[1] ?? 0),
			);
		}

		return (int) ($result['affected_rows'] ?? 0);
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
