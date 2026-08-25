<?php

namespace App\Shop\Repositories;

use App\Core\Database;
use App\Core\Repository;

class VatRepository extends Repository
{
  public function getVat(array $filters = []): array
  {
      return Database::call(
          'CALL sp_vat(?, ?, ?)',
          array_params(
              ['date_from', 'date_to', 'vat_previous'],
              $filters
          ),
      );
  }

	public function getVatPl011(array $filters = []): array
	{
			return Database::call(
					'CALL sp_vat_pl011(?, ?, ?, ?)',
					array_params(
							['date_from', 'date_to', 'page', 'per_page'],
							$filters
					),
			);
	}


	public function getVatPl012(array $filters = []): array
	{
			return Database::call(
					'CALL sp_vat_pl012(?, ?, ?, ?)',
					array_params(
							['date_from', 'date_to', 'page', 'per_page'],
							$filters
					),
			);
	}

	/* =================================================
		VAT PL204
	================================================= */

	public function getVatPl204(array $filters = []): array
	{
			return Database::call(
					'CALL sp_vat_pl204(?, ?, ?, ?)',
					array_params(
							['date_from', 'date_to', 'page', 'per_page'],
							$filters
					),
			);
	}
}