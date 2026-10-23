<?php

namespace App\Hrm\Controllers;

use App\Core\View;

class AttendanceController
{
    /**
     * Chấm công
     */
    public function index(): void
    {
        View::render('attendance/index');
    }
}