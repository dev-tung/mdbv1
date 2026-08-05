<?php

use App\Core\Router;
use App\Website\Controllers\CartController;
use App\Website\Controllers\HomeController;
use App\Website\Controllers\PageController;
use App\Website\Controllers\ShopController;

// =========================
// HOME
// =========================

Router::get('/', [HomeController::class, 'index']);

// =========================
// SHOP
// =========================

Router::get('/product', [ShopController::class, 'index']);
Router::get('/product/{slug}', [ShopController::class, 'show']);
Router::get('/category/{slug}', [ShopController::class, 'category']);
Router::get('/search', [ShopController::class, 'search']);

// =========================
// CART
// =========================

Router::get('/cart', [CartController::class, 'index']);
Router::get('/checkout', [CartController::class, 'checkout']);
Router::get('/checkout/success', [CartController::class, 'success']);

// =========================
// PAGE
// =========================

Router::get('/string', [PageController::class, 'string']);
Router::get('/affiliate', [PageController::class, 'affiliate']);
Router::get('/career', [PageController::class, 'career']);
Router::get('/contact', [PageController::class, 'contact']);
Router::get('/warranty-policy', [PageController::class, 'warrantyPolicy']);
Router::get('/shipping-policy', [PageController::class, 'shippingPolicy']);
Router::get('/return-policy', [PageController::class, 'returnPolicy']);
