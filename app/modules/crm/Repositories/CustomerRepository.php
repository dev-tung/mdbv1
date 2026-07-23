<?php

class CustomerRepository extends Repository
{
	protected string $table = 'customers';

	/* =================================================
       LIST
    ================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_customer_list(?, ?, ?, ?, ?)',
			array_params(['keyword', 'date_from', 'date_to', 'page', 'per_page'], $filters),
		);
	}

	/* =================================================
       BUILD DATA
    ================================================= */

	private function buildData(array $data): array
	{
		return [
			'name' => $data['name'],

			'group_id' => $data['group_id'] ?? null,

			'phone' => $data['phone'],

			'email' => $data['email'],

			'address' => $data['address'],

			'description' => $data['description'],
		];
	}

	/* =================================================
       CREATE
    ================================================= */

	public function create(array $data): int
	{
		return parent::create($this->buildData($data));
	}

	/* =================================================
       UPDATE
    ================================================= */

	public function update(int $id, array $data): bool
	{
		if (!parent::findById($id)) {
			return false;
		}

		return parent::updateById($id, $this->buildData($data)) > 0;
	}

	/* =================================================
       DELETE
    ================================================= */

	public function delete(int $id): bool
	{
		if (!parent::findById($id)) {
			return false;
		}

		return parent::deleteById($id) > 0;
	}
}
