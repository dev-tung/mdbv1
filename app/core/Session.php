<?php

class Session
{
	// =========================
	// START SESSION
	// =========================
	public static function start()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
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
