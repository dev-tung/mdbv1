<?php

use App\Shop\Controllers\BrandController;
use App\Shop\Controllers\CategoryController;
use App\Shop\Controllers\OrderController;
use App\Shop\Controllers\ProductController;
use App\Shop\Controllers\PurchaseController;
use App\Shop\Controllers\ReportController;
use App\Shop\Controllers\SupplierController;

// =========================
// PRODUCTS
// =========================

Router::get('/admin/products', [ProductController::class, 'index']);
Router::get('/admin/products/create', [ProductController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/products/edit/{id}', [ProductController::class, 'form'], ['auth' => 'admin']);

// =========================
// CATEGORIES
// =========================

Router::get('/admin/categories', [CategoryController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/categories/create', [CategoryController::class, 'create'], ['auth' => 'admin']);
Router::get('/admin/categories/edit/{id}', [CategoryController::class, 'edit'], ['auth' => 'admin']);

// =========================
// BRANDS
// =========================

Router::get('/admin/brands', [BrandController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/brands/create', [BrandController::class, 'create'], ['auth' => 'admin']);
Router::get('/admin/brands/edit/{id}', [BrandController::class, 'edit'], ['auth' => 'admin']);

// =========================
// SUPPLIERS
// =========================

Router::get('/admin/suppliers', [SupplierController::class, 'index']);
Router::get('/admin/suppliers/create', [SupplierController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/suppliers/edit/{id}', [SupplierController::class, 'form'], ['auth' => 'admin']);

// =========================
// PURCHASES
// =========================

Router::get('/admin/purchases', [PurchaseController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/purchases/create', [PurchaseController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/purchases/edit/{id}', [PurchaseController::class, 'form'], ['auth' => 'admin']);

// =========================
// ORDERS
// =========================

Router::get('/admin/orders', [OrderController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/orders/create', [OrderController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/orders/edit/{id}', [OrderController::class, 'form'], ['auth' => 'admin']);

// =========================
// REPORT
// =========================

Router::get('/admin/shop/revenue', [ReportController::class, 'revenue'], ['auth' => 'admin']);
Router::get('/admin/shop/inventory', [ReportController::class, 'inventory'], ['auth' => 'admin']);
Router::get('/admin/shop/customer', [ReportController::class, 'customer'], ['auth' => 'admin']);
