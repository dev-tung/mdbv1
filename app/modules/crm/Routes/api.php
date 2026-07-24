<?php

use App\Core\Router;
use App\Crm\Endpoints\CustomerEndpoint;

// =========================
// CUSTOMER
// =========================

Router::get(
	'/api/customers',
	[CustomerEndpoint::class, 'apiList'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/customers',
	[CustomerEndpoint::class, 'apiCreate'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/api/customers/show/{id}',
	[CustomerEndpoint::class, 'apiShow'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/customers/update/{id}',
	[CustomerEndpoint::class, 'apiUpdate'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/customers/delete/{id}',
	[CustomerEndpoint::class, 'apiDelete'],
	[
		'auth' => 'admin',
	],
);
