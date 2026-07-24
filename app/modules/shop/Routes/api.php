<?php

use App\Shop\Endpoints\BrandEndpoint;
use App\Shop\Endpoints\CategoryEndpoint;
use App\Shop\Endpoints\OrderEndpoint;
use App\Shop\Endpoints\ProductEndpoint;
use App\Shop\Endpoints\PurchaseEndpoint;
use App\Shop\Endpoints\ReportEndpoint;
use App\Shop\Endpoints\SupplierEndpoint;
use App\Shop\Endpoints\WarehouseEndpoint;

// =========================
// PRODUCT
// =========================

Router::get('/api/shop/products', [ProductEndpoint::class, 'apiList']);

Router::post('/api/shop/products', [ProductEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/shop/products/show/{id}', [ProductEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/shop/products/update/{id}', [ProductEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/shop/products/delete/{id}', [ProductEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// CATEGORY
// =========================

Router::get('/api/shop/categories', [CategoryEndpoint::class, 'apiList']);

Router::post('/api/shop/categories', [CategoryEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/shop/categories/show/{id}', [CategoryEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/shop/categories/update/{id}', [CategoryEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/shop/categories/delete/{id}', [CategoryEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// BRAND
// =========================

Router::get('/api/shop/brands', [BrandEndpoint::class, 'apiList']);

Router::post('/api/shop/brands', [BrandEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/shop/brands/show/{id}', [BrandEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/shop/brands/update/{id}', [BrandEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/shop/brands/delete/{id}', [BrandEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// SUPPLIER
// =========================

Router::get('/api/shop/suppliers', [SupplierEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/shop/suppliers', [SupplierEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/shop/suppliers/show/{id}', [SupplierEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/shop/suppliers/update/{id}', [SupplierEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/shop/suppliers/delete/{id}', [SupplierEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// WAREHOUSE
// =========================

Router::get('/api/shop/warehouses', [WarehouseEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/shop/warehouses', [WarehouseEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/shop/warehouses/show/{id}', [WarehouseEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/shop/warehouses/update/{id}', [WarehouseEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/shop/warehouses/delete/{id}', [WarehouseEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// PURCHASE
// =========================

Router::get('/api/shop/purchases', [PurchaseEndpoint::class, 'apiList'], ['auth' => 'admin']);

Router::post('/api/shop/purchases', [PurchaseEndpoint::class, 'apiCreate'], ['auth' => 'admin']);

Router::get('/api/shop/purchases/show/{id}', [PurchaseEndpoint::class, 'apiShow'], ['auth' => 'admin']);

Router::post('/api/shop/purchases/update/{id}', [PurchaseEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);

Router::post('/api/shop/purchases/delete/{id}', [PurchaseEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

Router::post('/api/shop/purchases/status', [PurchaseEndpoint::class, 'apiStatus'], ['auth' => 'admin']);

Router::post('/api/shop/purchases/payment', [PurchaseEndpoint::class, 'apiPayment'], ['auth' => 'admin']);


// =========================
// ORDER
// =========================

Router::get('/api/shop/orders', [OrderEndpoint::class, 'apiList'], ['auth' => 'admin']);

Router::post('/api/shop/orders', [OrderEndpoint::class, 'apiCreate'], ['auth' => 'admin']);

Router::get('/api/shop/orders/show/{id}', [OrderEndpoint::class, 'apiShow'], ['auth' => 'admin']);

Router::post('/api/shop/orders/update/{id}', [OrderEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);

Router::post('/api/shop/orders/delete/{id}', [OrderEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

Router::post('/api/shop/orders/status', [OrderEndpoint::class, 'apiStatus'], ['auth' => 'admin']);

Router::post('/api/shop/orders/payment', [OrderEndpoint::class, 'apiPayment'], ['auth' => 'admin']);

// =========================
// REPORT
// =========================

Router::get('/api/shop/inventory', [ReportEndpoint::class, 'apiInventory'], ['auth' => 'admin']);
Router::get('/api/shop/revenue', [ReportEndpoint::class, 'apiRevenue'], ['auth' => 'admin']);
Router::get('/api/shop/buyer', [ReportEndpoint::class, 'apiBuyer'], ['auth' => 'admin']);
