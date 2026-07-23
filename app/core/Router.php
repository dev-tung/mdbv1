<?php

namespace App\Core;

class Router
{
	protected static array $routes = [];

	// =========================
	// REGISTER GET
	// =========================

	public static function get(string $uri, array $handler, array $middleware = []): void
	{
		self::$routes['GET'][] = [
			'uri' => $uri,
			'handler' => $handler,
			'middleware' => $middleware,
		];
	}

	// =========================
	// REGISTER POST
	// =========================

	public static function post(string $uri, array $handler, array $middleware = []): void
	{
		self::$routes['POST'][] = [
			'uri' => $uri,
			'handler' => $handler,
			'middleware' => $middleware,
		];
	}

	// =========================
	// DISPATCH
	// =========================

	public static function dispatch(string $method, string $uri): void
	{
		$method = strtoupper($method);

		foreach (self::$routes[$method] ?? [] as $route) {
			$pattern = self::convertUriToRegex($route['uri']);

			if (!preg_match($pattern, $uri, $matches)) {
				continue;
			}

			array_shift($matches);

			// Detect module
			[$controller] = $route['handler'];

			$parts = explode('\\', $controller);

			View::setModule(strtolower($parts[1] ?? 'website'));

			// Middleware
			Middleware::handle($route['middleware'] ?? []);

			// Controller action
			self::callAction($route['handler'], $matches);

			return;
		}

		http_response_code(404);

		echo "404 NOT FOUND: {$uri}";
	}

	// =========================
	// CONVERT ROUTE PARAMS
	// =========================

	protected static function convertUriToRegex(string $uri): string
	{
		$pattern = preg_replace_callback('#\{([a-zA-Z_]+)\}#', fn() => '([a-zA-Z0-9_-]+)', $uri);

		return "#^{$pattern}$#";
	}

	// =========================
	// CALL ACTION
	// =========================

	protected static function callAction(array $handler, array $params = []): void
	{
		[$controller, $action] = $handler;

		if (!class_exists($controller)) {
			die("Class not found: {$controller}");
		}

		$instance = new $controller();

		if (!method_exists($instance, $action)) {
			die("Method not found: {$controller}::{$action}");
		}

		call_user_func_array([$instance, $action], $params);
	}
}
