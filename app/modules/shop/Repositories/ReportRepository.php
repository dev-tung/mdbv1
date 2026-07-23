<?php

namespace App\Shop\Repositories;

use App\Core\Database;
use App\Core\Repository;

class ReportRepository extends Repository
{
	public function getInventory(array $filters = []): array
	{
		return Database::call(
			'CALL sp_report_inventory(?, ?, ?, ?)',
			array_params(
				['keyword', 'product_id', 'purchase_id', 'stock'],
				$filters,
			),
		);
	}
}