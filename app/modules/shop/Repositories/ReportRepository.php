<?php

namespace App\Shop\Repositories;

use App\Core\Database;
use App\Core\Repository;

class ReportRepository extends Repository
{
	public function getRevenue(array $filters = []): array
	{
			return Database::call(
					'CALL sp_report_revenue(?, ?, ?, ?)',
					array_params(
							['mode', 'date', 'month', 'year'],
							$filters,
					),
			);
	}

	public function getInventory(array $filters = []): array
	{
		return Database::call(
			'CALL sp_report_inventory(?, ?, ?, ?)',
			array_params(['keyword', 'product_id', 'purchase_id', 'stock'], $filters),
		);
	}
}
