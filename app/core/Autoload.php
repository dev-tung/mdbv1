<?php

class Autoload
{
	public static function register(): void
	{
		spl_autoload_register(function ($class) {
			$paths = [
				PATH_ROOT . '/app/core/' . $class . '.php',
				PATH_ROOT . '/app/modules/*/validators/' . $class . '.php',
				PATH_ROOT . '/app/modules/*/repositories/' . $class . '.php',
				PATH_ROOT . '/app/modules/*/controllers/' . $class . '.php',
				PATH_ROOT . '/app/modules/*/endpoints/' . $class . '.php',
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
