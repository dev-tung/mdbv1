<?php

namespace App\Core;

class Middleware
{
	public static function handle(array $middlewares = []): void
	{
		$auth = $middlewares['auth'] ?? null;

		switch ($auth) {
			case 'admin':
				self::admin();
				break;

			case 'admin_api':
				self::adminApi();
				break;

			case 'customer':
				self::customer();
				break;
		}
	}

	/* =================================================
	   ADMIN
	================================================= */

	protected static function admin(): void
	{
		if (Auth::check() && Auth::hasRole('admin')) {
			return;
		}

		Response::redirect('/admin/login');
	}

	protected static function adminApi(): void
	{
		if (Auth::check() && Auth::hasRole('admin')) {
			return;
		}

		Response::error('Unauthorized', 401);
	}

	/* =================================================
	   CUSTOMER
	================================================= */

	protected static function customer(): void
	{
		if (Auth::check() && Auth::hasRole('customer')) {
			return;
		}

		Response::redirect('/');
	}
}
