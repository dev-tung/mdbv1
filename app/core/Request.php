<?php

class Request
{
	public static function method(): string
	{
		return $_SERVER['REQUEST_METHOD'] ?? 'GET';
	}

	public static function path(): string
	{
		$uri = $_SERVER['REQUEST_URI'] ?? '/';

		$path = parse_url($uri, PHP_URL_PATH);

		// remove trailing slash
		$path = rtrim($path, '/');

		// remove index.php nếu có
		$path = str_replace('/index.php', '', $path);

		return $path === '' ? '/' : $path;
	}

	// =========================
	// JSON BODY
	// =========================
	public static function json(): array
	{
		return json_decode(file_get_contents('php://input'), true) ?? [];
	}

	// =========================
	// INPUT
	// =========================
	public static function input(string $key, $default = null)
	{
		$data = self::json();

		return $data[$key] ?? ($_POST[$key] ?? ($_GET[$key] ?? $default));
	}

	// =========================
	// ALL INPUTS
	// =========================
	public static function all(): array
	{
		return array_merge($_GET, $_POST, self::json());
	}

	// =========================
	// ONLY
	// =========================
	public static function only(array $keys): array
	{
		$result = [];

		foreach ($keys as $key) {
			$result[$key] = self::input($key);
		}

		return $result;
	}
}
