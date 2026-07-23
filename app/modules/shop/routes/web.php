<?php

use App\Modules\Shop\Controllers\BrandController;
use App\Modules\Shop\Controllers\CategoryController;
use App\Modules\Shop\Controllers\OrderController;
use App\Modules\Shop\Controllers\ProductController;
use App\Modules\Shop\Controllers\PurchaseController;
use App\Modules\Shop\Controllers\ReportController;
use App\Modules\Shop\Controllers\SupplierController;

// =========================
// PRODUCTS
// =========================

Router::get('/admin/products', 'ProductController@index');
Router::get('/admin/products/create', 'ProductController@form', [
	'auth' => 'admin',
]);
Router::get('/admin/products/edit/{id}', 'ProductController@form', [
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
// SUPPLIERS
// =========================

Router::get('/admin/suppliers', 'SupplierController@index');

Router::get('/admin/suppliers/create', 'SupplierController@form', [
	'auth' => 'admin',
]);

Router::get('/admin/suppliers/edit/{id}', 'SupplierController@form', [
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

// =========================
// REPORT
// =========================

Router::get('/admin/shop/revenue', 'ReportController@revenue', [
	'auth' => 'admin',
]);
Router::get('/admin/shop/inventory', 'ReportController@inventory', [
	'auth' => 'admin',
]);
Router::get('/admin/shop/customer', 'ReportController@customer', [
	'auth' => 'admin',
]);