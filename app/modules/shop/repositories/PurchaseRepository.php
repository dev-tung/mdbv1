<?php

class PurchaseRepository extends Repository
{
	protected string $table = 'purchases';

	/* =================================================
       LIST
    ================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_purchase_list(?, ?, ?, ?, ?, ?)',
			array_params(['date_from', 'date_to', 'supplier', 'payment', 'page', 'per_page'], $filters),
		);
	}

	/* =================================================
       SHOW
    ================================================= */

	public function show(int $id): array
	{
		return Database::call('CALL sp_purchase_show(:id)', [
			'id' => $id,
		]);
	}

	/* =================================================
       CREATE
    ================================================= */

	public function create(array $data): int
	{
		Database::query(
			'CALL sp_purchase_create(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )',
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
			'CALL sp_purchase_update(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )',
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

				json_encode($data['items']),
			],
		);
	}

	/* =================================================
    DELETE
    ================================================= */

	public function delete(int $id): void
	{
		Database::query('CALL sp_purchase_delete(?)', [$id]);
	}
}
