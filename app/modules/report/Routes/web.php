<?php

use App\Core\Router;
use App\Report\Controllers\VatController;

Router::get('/admin/vat', [VatController::class, 'index']);
Router::get('/admin/vat/pl011', [VatController::class, 'pl011']);
Router::get('/admin/vat/pl012', [VatController::class, 'pl012']);
Router::get('/admin/vat/pl204', [VatController::class, 'pl204']);
