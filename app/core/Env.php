<?php

class Env
{
	private static bool $loaded = false;

	public static function init(string $path = null): void
	{
		if (self::$loaded) {
			return;
		}

		$path = $path ?? BASE_PATH . '/.env';

		if (!file_exists($path)) {
			return;
		}

		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		foreach ($lines as $line) {
			$line = trim($line);

			// bỏ comment / dòng rỗng
			if ($line === '' || str_starts_with($line, '#')) {
				continue;
			}

			// phải có dấu =
			if (!str_contains($line, '=')) {
				continue;
			}

			[$key, $value] = explode('=', $line, 2);

			$key = trim($key);
			$value = trim($value, "\"'");

			$_ENV[$key] = $value;
			$_SERVER[$key] = $value;
		}

		self::$loaded = true;
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		self::init(); // 🔥 auto init nếu chưa load

		return $_ENV[$key] ?? $default;
	}

	public static function has(string $key): bool
	{
		self::init();

		return isset($_ENV[$key]);
	}
}
