<?php

use App\Core\Router;
use App\Hrm\Endpoints\EmployeeEndpoint;
use App\Hrm\Endpoints\AttendanceEndpoint;

// =========================
// EMPLOYEE
// =========================

Router::get(
    '/api/employees',
    [EmployeeEndpoint::class, 'apiList'],
    [
        'auth' => 'admin',
    ],
);

Router::post(
    '/api/employees',
    [EmployeeEndpoint::class, 'apiCreate'],
    [
        'auth' => 'admin',
    ],
);

Router::get(
    '/api/employees/show/{id}',
    [EmployeeEndpoint::class, 'apiShow'],
    [
        'auth' => 'admin',
    ],
);

Router::post(
    '/api/employees/update/{id}',
    [EmployeeEndpoint::class, 'apiUpdate'],
    [
        'auth' => 'admin',
    ],
);

Router::post(
    '/api/employees/delete/{id}',
    [EmployeeEndpoint::class, 'apiDelete'],
    [
        'auth' => 'admin',
    ],
);

// =========================
// ATTENDANCE
// =========================

Router::get(
    '/api/attendance',
    [AttendanceEndpoint::class, 'apiList'],
    [
        'auth' => 'admin',
    ],
);

Router::post(
    '/api/attendance/in',
    [AttendanceEndpoint::class, 'apiIn'],
    [
        'auth' => 'admin',
    ],
);

Router::post(
    '/api/attendance/out',
    [AttendanceEndpoint::class, 'apiOut'],
    [
        'auth' => 'admin',
    ],
);