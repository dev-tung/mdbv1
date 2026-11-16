<?php

use App\Core\Router;
use App\Website\Controllers\CartController;
use App\Website\Controllers\HomeController;
use App\Website\Controllers\PageController;
use App\Website\Controllers\ShopController;
use App\Website\Endpoints\OnlineOrderEndpoint;
use App\Website\Endpoints\OnlinePaymentEndpoint;

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
Router::get('/sale', [ShopController::class, 'sale']);

// =========================
// CART
// =========================

Router::get('/cart', [CartController::class, 'index']);
Router::get('/checkout', [CartController::class, 'checkout']);
Router::get('/checkout/success', [CartController::class, 'success']);
Router::get('/payment', [CartController::class, 'payment']);
Router::get('/order/status', [CartController::class, 'status']);
Router::post('/api/online-orders', [OnlineOrderEndpoint::class, 'create']);
Router::post('/api/online-orders/payment-qr', [OnlinePaymentEndpoint::class, 'qr']);

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