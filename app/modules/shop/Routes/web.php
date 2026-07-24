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

Router::get('/admin/shop/products', [ProductController::class, 'index']);
Router::get('/admin/shop/products/create', [ProductController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/shop/products/edit/{id}', [ProductController::class, 'form'], ['auth' => 'admin']);

// =========================
// CATEGORIES
// =========================

Router::get('/admin/shop/categories', [CategoryController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/shop/categories/create', [CategoryController::class, 'create'], ['auth' => 'admin']);
Router::get('/admin/shop/categories/edit/{id}', [CategoryController::class, 'edit'], ['auth' => 'admin']);

// =========================
// BRANDS
// =========================

Router::get('/admin/shop/brands', [BrandController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/shop/brands/create', [BrandController::class, 'create'], ['auth' => 'admin']);
Router::get('/admin/shop/brands/edit/{id}', [BrandController::class, 'edit'], ['auth' => 'admin']);

// =========================
// SUPPLIERS
// =========================

Router::get('/admin/shop/suppliers', [SupplierController::class, 'index']);
Router::get('/admin/shop/suppliers/create', [SupplierController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/shop/suppliers/edit/{id}', [SupplierController::class, 'form'], ['auth' => 'admin']);

// =========================
// PURCHASES
// =========================

Router::get('/admin/shop/purchases', [PurchaseController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/shop/purchases/create', [PurchaseController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/shop/purchases/edit/{id}', [PurchaseController::class, 'form'], ['auth' => 'admin']);

// =========================
// ORDERS
// =========================

Router::get('/admin/shop/orders', [OrderController::class, 'index'], ['auth' => 'admin']);
Router::get('/admin/shop/orders/create', [OrderController::class, 'form'], ['auth' => 'admin']);
Router::get('/admin/shop/orders/edit/{id}', [OrderController::class, 'form'], ['auth' => 'admin']);

// =========================
// REPORT
// =========================

Router::get('/admin/shop/revenue', [ReportController::class, 'revenue'], ['auth' => 'admin']);
Router::get('/admin/shop/inventory', [ReportController::class, 'inventory'], ['auth' => 'admin']);
Router::get('/admin/shop/buyer', [ReportController::class, 'buyer'], ['auth' => 'admin']);
