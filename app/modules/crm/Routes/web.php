<?php

use App\Core\Router;
use App\Crm\Controllers\CustomerController;

// =========================
// CUSTOMERS
// =========================

Router::get('/admin/customers', [CustomerController::class, 'index']);

Router::get(
	'/admin/customers/create',
	[CustomerController::class, 'form'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/admin/customers/edit/{id}',
	[CustomerController::class, 'form'],
	[
		'auth' => 'admin',
	],
);
