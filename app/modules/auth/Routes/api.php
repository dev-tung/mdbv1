<?php

use App\Auth\Endpoints\AdminEndpoint;

Router::post('/api/admin/login', [AdminEndpoint::class, 'apiLogin']);
Router::post('/api/admin/logout', [AdminEndpoint::class, 'apiLogout'], ['auth' => 'admin_api']);
