<?php

use App\Core\Router;
use App\Report\Controllers\VatController;

Router::get('/admin/vat', [VatController::class, 'index']);