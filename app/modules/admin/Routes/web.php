<?php

use App\Admin\Controllers\AuthController;

// Admin Authentication
Router::get('/admin/login', [AuthController::class, 'login']);
Router::get('/admin/logout', [AuthController::class, 'logout']);