<?php

namespace App\Core;

class Autoload
{
	public static function register(): void
	{
		spl_autoload_register(function (string $class): void {
			// =========================
			// CORE
			// =========================

			if (str_starts_with($class, 'App\\Core\\')) {
				$file = PATH_ROOT . '/app/core/' . str_replace('App\\Core\\', '', $class) . '.php';

				$file = str_replace('\\', '/', $file);

				if (file_exists($file)) {
					require_once $file;
				}

				return;
			}

			// =========================
			// MODULE
			// =========================

			if (preg_match('/^App\\\\([^\\\\]+)\\\\(.+)$/', $class, $matches)) {
				$module = strtolower($matches[1]);

				$classPath = str_replace('\\', '/', $matches[2]);

				$file = PATH_ROOT . "/app/modules/{$module}/{$classPath}.php";

				if (file_exists($file)) {
					require_once $file;
				}
			}
		});
	}
}
