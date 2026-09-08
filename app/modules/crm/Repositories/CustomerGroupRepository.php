<?php

namespace App\CRM\Repositories;

use App\Core\Database;
use App\Core\Repository;

class CustomerGroupRepository extends Repository
{
	protected string $table = 'customer_groups';


  /* =================================================
    LIST
  ================================================= */

  public function getList(array $filters = []): array
  {
    return Database::get(
      'SELECT
        id,
        name
      FROM customer_groups
      ORDER BY id DESC'
    );
  }


	/* =================================================
	   BUILD DATA
	================================================= */

	private function buildData(array $data): array
	{
		return [
			'name' =>
				$data['name'] ?? null,
		];
	}


	/* =================================================
	   CREATE
	================================================= */

	public function create(array $data): int
	{
		return parent::create(
			$this->buildData($data)
		);
	}


	/* =================================================
	   UPDATE
	================================================= */

	public function update(
		int $id,
		array $data
	): bool
	{
		if (!parent::findById($id)) {
			return false;
		}

		return parent::updateById(
			$id,
			$this->buildData($data)
		) > 0;
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