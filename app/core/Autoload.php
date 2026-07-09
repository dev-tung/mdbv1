<?php

class Autoload
{
	public static function register(): void
	{
		spl_autoload_register(function ($class) {
			$paths = [
				BASE_PATH . '/app/core/' . $class . '.php',
				BASE_PATH . '/app/config/' . $class . '.php',
				BASE_PATH . '/app/modules/*/validators/' . $class . '.php',
				BASE_PATH . '/app/modules/*/repositories/' . $class . '.php',
				BASE_PATH . '/app/modules/*/controllers/' . $class . '.php',
				BASE_PATH . '/app/modules/*/endpoints/' . $class . '.php',
			];

			foreach ($paths as $pattern) {
				foreach (glob($pattern) as $file) {
					require_once $file;
					return;
				}
			}
		});
	}
}
