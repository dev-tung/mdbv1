<?php

// =========================
// CUSTOMER
// =========================

Router::get('/api/customers', 'CustomerEndpoint@apiList', [
	'auth' => 'admin',
]);

Router::post('/api/customers', 'CustomerEndpoint@apiCreate', [
	'auth' => 'admin',
]);

Router::get('/api/customers/show/{id}', 'CustomerEndpoint@apiShow', [
	'auth' => 'admin',
]);

Router::post('/api/customers/update/{id}', 'CustomerEndpoint@apiUpdate', [
	'auth' => 'admin',
]);

Router::post('/api/customers/delete/{id}', 'CustomerEndpoint@apiDelete', [
	'auth' => 'admin',
]);
