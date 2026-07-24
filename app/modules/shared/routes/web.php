<?php

Router::get('/login', 'WebsiteController@login');

Router::get('/register', 'WebsiteController@register');

Router::get('/forgot-password', 'WebsiteController@forgotPassword');

Router::post('/logout', 'WebsiteController@logout', ['auth']);

// =========================
// ADMIN
// =========================

Router::get('/admin/login', 'AdminController@login');

Router::get('/admin/logout', 'AdminController@logout');
