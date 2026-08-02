<?php

use App\Core\Router;
use App\Hrm\Controllers\EmployeeController;
use App\Hrm\Controllers\AttendanceController;

// =========================
// EMPLOYEES
// =========================

Router::get('/admin/employees', [EmployeeController::class, 'index']);

Router::get(
    '/admin/employees/create',
    [EmployeeController::class, 'form'],
    [
        'auth' => 'admin',
    ],
);

Router::get(
    '/admin/employees/edit/{id}',
    [EmployeeController::class, 'form'],
    [
        'auth' => 'admin',
    ],
);

// =========================
// ATTENDANCE
// =========================

Router::get(
    '/admin/attendance',
    [AttendanceController::class, 'index'],
    [
        'auth' => 'admin',
    ],
);