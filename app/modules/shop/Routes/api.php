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

Router::get('/api/products', [ProductEndpoint::class, 'apiList']);

Router::post('/api/products', [ProductEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/products/show/{id}', [ProductEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/products/update/{id}', [ProductEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/products/delete/{id}', [ProductEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// CATEGORY
// =========================

Router::get('/api/categories', [CategoryEndpoint::class, 'apiList']);

Router::post('/api/categories', [CategoryEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/categories/show/{id}', [CategoryEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/categories/update/{id}', [CategoryEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/categories/delete/{id}', [CategoryEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// BRAND
// =========================

Router::get('/api/brands', [BrandEndpoint::class, 'apiList']);

Router::post('/api/brands', [BrandEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/brands/show/{id}', [BrandEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/brands/update/{id}', [BrandEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/brands/delete/{id}', [BrandEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// SUPPLIER
// =========================

Router::get('/api/suppliers', [SupplierEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/suppliers', [SupplierEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/suppliers/show/{id}', [SupplierEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/suppliers/update/{id}', [SupplierEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/suppliers/delete/{id}', [SupplierEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// WAREHOUSE
// =========================

Router::get('/api/warehouses', [WarehouseEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/warehouses', [WarehouseEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/warehouses/show/{id}', [WarehouseEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/warehouses/update/{id}', [WarehouseEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/warehouses/delete/{id}', [WarehouseEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// PURCHASE
// =========================

Router::get('/api/purchases', [PurchaseEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/purchases', [PurchaseEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/purchases/show/{id}', [PurchaseEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/purchases/update/{id}', [PurchaseEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/purchases/delete/{id}', [PurchaseEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// ORDER
// =========================

Router::get('/api/orders', [OrderEndpoint::class, 'apiList'], ['auth' => 'admin']);
Router::post('/api/orders', [OrderEndpoint::class, 'apiCreate'], ['auth' => 'admin']);
Router::get('/api/orders/show/{id}', [OrderEndpoint::class, 'apiShow'], ['auth' => 'admin']);
Router::post('/api/orders/update/{id}', [OrderEndpoint::class, 'apiUpdate'], ['auth' => 'admin']);
Router::post('/api/orders/delete/{id}', [OrderEndpoint::class, 'apiDelete'], ['auth' => 'admin']);

// =========================
// REPORT
// =========================

Router::get('/api/inventory', [ReportEndpoint::class, 'apiInventory'], ['auth' => 'admin']);
Router::get('/api/revenue', [ReportEndpoint::class, 'apiRevenue'], ['auth' => 'admin']);
Router::get('/api/customer', [ReportEndpoint::class, 'apiCustomer'], ['auth' => 'admin']);