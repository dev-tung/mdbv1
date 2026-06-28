<?php

// =========================
// PRODUCT
// =========================
Router::get('/api/products', 'ProductEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/products', 'ProductEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/products/show/{id}', 'ProductEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/products/update', 'ProductEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/products/delete', 'ProductEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// CATEGORY
// =========================
Router::get('/api/categories', 'CategoryEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/categories', 'CategoryEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/categories/show/{id}', 'CategoryEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/categories/update', 'CategoryEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/categories/delete', 'CategoryEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// BRAND
// =========================
Router::get('/api/brands', 'BrandEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/brands', 'BrandEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/brands/show/{id}', 'BrandEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/brands/update', 'BrandEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/brands/delete', 'BrandEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// INVENTORY
// =========================
Router::get('/api/inventories', 'InventoryEndpoint@apiList', ['auth' => 'admin']);
Router::get('/api/inventories/stock', 'InventoryEndpoint@apiStock', ['auth' => 'admin']);
Router::post('/api/inventories', 'InventoryEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/inventories/show/{id}', 'InventoryEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/inventories/update', 'InventoryEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/inventories/delete', 'InventoryEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// SUPPLIER
// =========================
Router::get('/api/suppliers', 'SupplierEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/suppliers', 'SupplierEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/suppliers/show/{id}', 'SupplierEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/suppliers/update', 'SupplierEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/suppliers/delete', 'SupplierEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// WAREHOUSE
// =========================
Router::get('/api/warehouses', 'WarehouseEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/warehouses', 'WarehouseEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/warehouses/show/{id}', 'WarehouseEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/warehouses/update', 'WarehouseEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/warehouses/delete', 'WarehouseEndpoint@apiDelete', ['auth' => 'admin']);

// =========================
// PURCHASE
// =========================
Router::get('/api/purchases', 'PurchaseEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/purchases', 'PurchaseEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/purchases/show/{id}', 'PurchaseEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/purchases/update', 'PurchaseEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/purchases/delete', 'PurchaseEndpoint@apiDelete', ['auth' => 'admin']);
Router::post('/api/purchases/status', 'PurchaseEndpoint@apiStatus', ['auth' => 'admin']);
Router::post('/api/purchases/payment', 'PurchaseEndpoint@apiPayment', ['auth' => 'admin']);

// =========================
// ORDER
// =========================
Router::get('/api/orders', 'OrderEndpoint@apiList', ['auth' => 'admin']);
Router::post('/api/orders', 'OrderEndpoint@apiCreate', ['auth' => 'admin']);
Router::get('/api/orders/show/{id}', 'OrderEndpoint@apiShow', ['auth' => 'admin']);
Router::post('/api/orders/update', 'OrderEndpoint@apiUpdate', ['auth' => 'admin']);
Router::post('/api/orders/delete', 'OrderEndpoint@apiDelete', ['auth' => 'admin']);
Router::post('/api/orders/status', 'OrderEndpoint@apiStatus', ['auth' => 'admin']);
Router::post('/api/orders/payment', 'OrderEndpoint@apiPayment', ['auth' => 'admin']);