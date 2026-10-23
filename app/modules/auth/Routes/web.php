<?php

use App\Auth\Controllers\AdminController;

// Admin Adminentication
Router::get('/admin/login', [AdminController::class, 'login']);
Router::get('/admin/logout', [AdminController::class, 'logout']);
