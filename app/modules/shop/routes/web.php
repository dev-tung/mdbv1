<?php

// =========================
// PRODUCTS
// =========================

Router::get('/admin/products', 'ProductController@index');
Router::get('/admin/products/create', 'ProductController@create', [
	'auth' => 'admin',
]);
Router::get('/admin/products/edit/{id}', 'ProductController@edit', [
	'auth' => 'admin',
]);

// =========================
// CATEGORIES
// =========================

Router::get('/admin/categories', 'CategoryController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/categories/create', 'CategoryController@create', [
	'auth' => 'admin',
]);
Router::get('/admin/categories/edit/{id}', 'CategoryController@edit', [
	'auth' => 'admin',
]);

// =========================
// BRANDS
// =========================

Router::get('/admin/brands', 'BrandController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/brands/create', 'BrandController@create', [
	'auth' => 'admin',
]);
Router::get('/admin/brands/edit/{id}', 'BrandController@edit', [
	'auth' => 'admin',
]);

// =========================
// INVENTORY
// =========================

Router::get('/admin/inventory', 'InventoryController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/inventory/create', 'InventoryController@create', [
	'auth' => 'admin',
]);
Router::get('/admin/inventory/edit/{id}', 'InventoryController@edit', [
	'auth' => 'admin',
]);

// =========================
// SUPPLIERS
// =========================

Router::get('/admin/suppliers', 'SupplierController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/suppliers/create', 'SupplierController@create', [
	'auth' => 'admin',
]);
Router::get('/admin/suppliers/edit/{id}', 'SupplierController@edit', [
	'auth' => 'admin',
]);

// =========================
// PURCHASES
// =========================

Router::get('/admin/purchases', 'PurchaseController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/purchases/create', 'PurchaseController@form', [
	'auth' => 'admin',
]);
Router::get('/admin/purchases/edit/{id}', 'PurchaseController@form', [
	'auth' => 'admin',
]);

// =========================
// ORDERS
// =========================

Router::get('/admin/orders', 'OrderController@index', [
	'auth' => 'admin',
]);
Router::get('/admin/orders/create', 'OrderController@form', [
	'auth' => 'admin',
]);
Router::get('/admin/orders/edit/{id}', 'OrderController@form', [
	'auth' => 'admin',
]);
