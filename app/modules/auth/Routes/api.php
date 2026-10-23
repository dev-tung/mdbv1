<?php

use App\Auth\Endpoints\AdminEndpoint;

Router::post('/api/admin/login', [AdminEndpoint::class, 'apiLogin']);
