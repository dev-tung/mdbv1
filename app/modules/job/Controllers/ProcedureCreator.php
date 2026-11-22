<?php

namespace App\Job\Controllers;

use App\Core\Database;

class ProcedureCreator
{
	public function run(): void
	{
		$files = glob(PATH_ROOT . '/app/database/procedure/*.sql');

		sort($files);
		$synced = [];

		foreach ($files as $file) {
			Database::raw(file_get_contents($file));
			$synced[] = basename($file);
		}

		echo implode('<br>', array_map(
			static fn (string $file): string => 'Sync: ' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8'),
			$synced,
		));
		echo 'Done';
	}
}
