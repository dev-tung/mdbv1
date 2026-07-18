<?php

class BrandRepository extends Repository
{
	protected string $table = 'brands';

	/* =================================================
	   LIST
	================================================= */

	public function getList(array $filters = []): array
	{
		return Database::call(
			'CALL sp_brand_list(?, ?, ?, ?, ?)',
			array_params(
				['keyword', 'date_from', 'date_to', 'page', 'per_page'],
				$filters,
			),
		);
	}

	/* =================================================
	   BUILD DATA
	================================================= */

	private function buildData(array $data): array
	{
		return [
			'name' => $data['name'] ?? null,

			'description' => $data['description'] ?? null,
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