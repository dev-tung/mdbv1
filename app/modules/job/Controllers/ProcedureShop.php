<?php

namespace App\Job\Controllers;

use App\Core\Database;

class ProcedureShop
{
	public function run(): void
	{
		$files = glob(PATH_ROOT . '/app/modules/shop/Database/procedures/*.sql');

		sort($files);

		foreach ($files as $file) {
			echo 'Sync: ' . basename($file) . PHP_EOL . '<br>';

			Database::raw(file_get_contents($file));
		}

		echo 'Done';
	}
}
