<?php

use App\Core\Router;

use App\Website\Controllers\HomeController;
use App\Shop\Controllers\ShopController;
use App\Shop\Controllers\CartController;
use App\Website\Controllers\PageController;

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
Router::get('/cart/success', [CartController::class, 'success']);

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
