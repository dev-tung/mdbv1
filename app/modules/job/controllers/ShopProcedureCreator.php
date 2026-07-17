<?php

class ShopProcedureCreator
{
	public function run(): void
	{
		$files = glob(PATH_ROOT . '/app/modules/shop/database/procedures/*.sql');

		sort($files);

		foreach ($files as $file) {
			echo 'Sync: ' . basename($file) . PHP_EOL . '<br>';

			Database::raw(file_get_contents($file));
		}

		echo 'Done';
	}
}
