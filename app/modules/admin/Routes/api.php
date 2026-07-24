<?php

use App\Admin\Endpoints\AuthEndpoint;

Router::post('/api/admin/login', [AuthEndpoint::class, 'apiLogin']);
