<?php

use App\Core\Router;
use App\Crm\Controllers\CustomerController;

// =========================
// CUSTOMERS
// =========================

Router::get('/admin/crm/customers', [CustomerController::class, 'index']);

Router::get(
	'/admin/crm/customers/create',
	[CustomerController::class, 'form'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/admin/crm/customers/edit/{id}',
	[CustomerController::class, 'form'],
	[
		'auth' => 'admin',
	],
);
