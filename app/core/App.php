<?php

namespace App\Core;

class App
{
	public function run(): void
	{
		$this->registerErrorHandler();

		$this->loadHelpers();

		$this->loadRoutes();

		Router::dispatch(
			Request::method(),
			Request::path()
		);
	}

	// =========================
	// LOAD ROUTES
	// =========================

	protected function loadRoutes(): void
	{
		$routes = glob(PATH_ROOT . '/app/modules/*/Routes/*.php');

		foreach ($routes as $file) {
			require_once $file;
		}
	}


	// =========================
	// ERROR HANDLER
	// =========================

	protected function registerErrorHandler(): void
	{
		set_exception_handler(function (\Throwable $e): void {

			http_response_code(500);

			echo Response::json([
				'success' => false,
				'message' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
			]);
		});
	}

	// =========================
	// LOAD HELPERS
	// =========================

	protected function loadHelpers(): void
	{
		foreach (glob(PATH_ROOT . '/app/common/helpers/*.php') as $file) {
			require_once $file;
		}

		$aliases = [
			'View'     => View::class,
			'Auth'     => Auth::class,
			'Request'  => Request::class,
			'Response' => Response::class,
			'Router'   => Router::class,
			'Env'      => Env::class,
		];

		foreach ($aliases as $alias => $class) {
			if (!class_exists($alias)) {
				class_alias($class, $alias);
			}
		}
	}
}