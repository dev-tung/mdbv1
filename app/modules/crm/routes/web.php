<?php

// =========================
// CUSTOMERS
// =========================

Router::get('/admin/customers', 'CustomerController@index');

Router::get('/admin/customers/create', 'CustomerController@form', [
	'auth' => 'admin',
]);

Router::get('/admin/customers/edit/{id}', 'CustomerController@form', [
	'auth' => 'admin',
]);
