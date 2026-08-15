<?php

use App\Core\Router;
use App\Crm\Endpoints\CustomerEndpoint;
use App\Crm\Endpoints\CustomerGroupEndpoint;

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


// =========================
// CUSTOMER GROUP
// =========================

Router::get(
	'/api/customer-groups',
	[CustomerGroupEndpoint::class, 'apiList'],
	[
		'auth' => 'admin',
	],
);

Router::post(
	'/api/customer-groups',
	[CustomerGroupEndpoint::class, 'apiCreate'],
	[
		'auth' => 'admin',
	],
);