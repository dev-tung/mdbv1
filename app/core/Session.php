<?php

namespace App\Core;

class Session
{
	// =========================
	// START SESSION
	// =========================
	public static function start()
	{
		if (session_status() === PHP_SESSION_NONE) {
			$authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

			if ($authorization === '' && function_exists('getallheaders')) {
				foreach (getallheaders() as $name => $value) {
					if (strcasecmp($name, 'Authorization') === 0) {
						$authorization = $value;
						break;
					}
				}
			}

			if (preg_match('/^Bearer ([a-zA-Z0-9,-]{16,128})$/D', $authorization, $matches)) {
				session_id($matches[1]);
			}

			session_start();
		}
	}

	public static function regenerateId(): string
	{
		self::start();

		if (!session_regenerate_id(true)) {
			throw new \RuntimeException('Could not regenerate the session ID');
		}

		return session_id();
	}

	// =========================
	// SET
	// =========================
	public static function set($key, $value)
	{
		self::start();
		$_SESSION[$key] = $value;
	}

	// =========================
	// GET
	// =========================
	public static function get($key, $default = null)
	{
		self::start();

		return $_SESSION[$key] ?? $default;
	}

	// =========================
	// HAS
	// =========================
	public static function has($key)
	{
		self::start();

		return isset($_SESSION[$key]);
	}

	// =========================
	// REMOVE
	// =========================
	public static function remove($key)
	{
		self::start();
		unset($_SESSION[$key]);
	}

	// =========================
	// DESTROY ALL
	// =========================
	public static function destroy()
	{
		self::start();
		session_unset();
		session_destroy();
	}

	// =========================
	// FLASH DATA (optional nhưng rất nên có)
	// =========================
	public static function flash($key, $value = null)
	{
		self::start();

		if ($value === null) {
			$value = $_SESSION['_flash'][$key] ?? null;
			unset($_SESSION['_flash'][$key]);

			return $value;
		}

		$_SESSION['_flash'][$key] = $value;
	}
}
