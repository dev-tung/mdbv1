<?php

// =========================
// HOME
// =========================

Router::get('/', 'HomeController@index');

// =========================
// SHOP
// =========================

Router::get('/product', 'ShopController@index');
Router::get('/product/{slug}', 'ShopController@show');
Router::get('/category/{slug}', 'ShopController@category');
Router::get('/search', 'ShopController@search');

// =========================
// CART
// =========================

Router::get('/cart', 'CartController@index');
Router::get('/checkout', 'CartController@checkout');
Router::get('/cart/success', 'CartController@success');

// =========================
// PAGE
// =========================

Router::get('/string', 'PageController@string');
Router::get('/affiliate', 'PageController@affiliate');
Router::get('/career', 'PageController@career');
Router::get('/contact', 'PageController@contact');
Router::get('/warranty-policy', 'PageController@warrantyPolicy');
Router::get('/shipping-policy', 'PageController@shippingPolicy');
Router::get('/return-policy', 'PageController@returnPolicy');