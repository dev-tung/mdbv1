<?php

// =========================
// PRODUCT
// =========================
Router::get('/api/products', 'ProductEndpoint@apiList');

Router::post('/api/products', 'ProductEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/products/show/{id}', 'ProductEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/products/update/{id}', 'ProductEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/products/delete/{id}', 'ProductEndpoint@apiDelete', [
	'auth' => 'admin',
]);

// =========================
// CATEGORY
// =========================
Router::get('/api/categories', 'CategoryEndpoint@apiList');

Router::post('/api/categories', 'CategoryEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/categories/show/{id}', 'CategoryEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/categories/update/{id}', 'CategoryEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/categories/delete/{id}', 'CategoryEndpoint@apiDelete', [
	'auth' => 'admin',
]);

// =========================
// BRAND
// =========================
Router::get('/api/brands', 'BrandEndpoint@apiList');

Router::post('/api/brands', 'BrandEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/brands/show/{id}', 'BrandEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/brands/update/{id}', 'BrandEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/brands/delete/{id}', 'BrandEndpoint@apiDelete', [
	'auth' => 'admin',
]);

// =========================
// INVENTORY
// =========================
Router::get('/api/inventories', 'InventoryEndpoint@apiList', [
	'auth' => 'admin',
]);
Router::get('/api/inventories/stock', 'InventoryEndpoint@apiStock', [
	'auth' => 'admin',
]);
Router::post('/api/inventories', 'InventoryEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/inventories/show/{id}', 'InventoryEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/inventories/update/{id}', 'InventoryEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/inventories/delete/{id}', 'InventoryEndpoint@apiDelete', [
	'auth' => 'admin',
]);

Router::get('/api/inventories/quantity', 'InventoryEndpoint@apiQuantity', [
	'auth' => 'admin',
]);

// =========================
// SUPPLIER
// =========================
Router::get('/api/suppliers', 'SupplierEndpoint@apiList', [
	'auth' => 'admin',
]);
Router::post('/api/suppliers', 'SupplierEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/suppliers/show/{id}', 'SupplierEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/suppliers/update/{id}', 'SupplierEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/suppliers/delete/{id}', 'SupplierEndpoint@apiDelete', [
	'auth' => 'admin',
]);

// =========================
// WAREHOUSE
// =========================
Router::get('/api/warehouses', 'WarehouseEndpoint@apiList', [
	'auth' => 'admin',
]);
Router::post('/api/warehouses', 'WarehouseEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/warehouses/show/{id}', 'WarehouseEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/warehouses/update/{id}', 'WarehouseEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/warehouses/delete/{id}', 'WarehouseEndpoint@apiDelete', [
	'auth' => 'admin',
]);

// =========================
// PURCHASE
// =========================
Router::get('/api/purchases', 'PurchaseEndpoint@apiList', [
	'auth' => 'admin',
]);
Router::post('/api/purchases', 'PurchaseEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/purchases/show/{id}', 'PurchaseEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/purchases/update/{id}', 'PurchaseEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/purchases/delete/{id}', 'PurchaseEndpoint@apiDelete', [
	'auth' => 'admin',
]);
Router::post('/api/purchases/status', 'PurchaseEndpoint@apiStatus', [
	'auth' => 'admin',
]);
Router::post('/api/purchases/payment', 'PurchaseEndpoint@apiPayment', [
	'auth' => 'admin',
]);

// =========================
// ORDER
// =========================
Router::get('/api/orders', 'OrderEndpoint@apiList', [
	'auth' => 'admin',
]);
Router::post('/api/orders', 'OrderEndpoint@apiCreate', [
	'auth' => 'admin',
]);
Router::get('/api/orders/show/{id}', 'OrderEndpoint@apiShow', [
	'auth' => 'admin',
]);
Router::post('/api/orders/update/{id}', 'OrderEndpoint@apiUpdate', [
	'auth' => 'admin',
]);
Router::post('/api/orders/delete/{id}', 'OrderEndpoint@apiDelete', [
	'auth' => 'admin',
]);
Router::post('/api/orders/status', 'OrderEndpoint@apiStatus', [
	'auth' => 'admin',
]);
Router::post('/api/orders/payment', 'OrderEndpoint@apiPayment', [
	'auth' => 'admin',
]);
