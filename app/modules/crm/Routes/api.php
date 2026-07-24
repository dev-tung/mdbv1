<?php

use App\Core\Router;
use App\Crm\Endpoints\CustomerEndpoint;

// =========================
// CUSTOMER
// =========================

Router::get(
	'/api/crm/customers',
	[CustomerEndpoint::class, 'apiList'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/crm/customers',
	[CustomerEndpoint::class, 'apiCreate'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/api/crm/customers/show/{id}',
	[CustomerEndpoint::class, 'apiShow'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/crm/customers/update/{id}',
	[CustomerEndpoint::class, 'apiUpdate'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/crm/customers/delete/{id}',
	[CustomerEndpoint::class, 'apiDelete'],
	[
		'auth' => 'admin',
	],
);
