<?php

namespace App\HRM\Controllers;

use App\Core\View;

class EmployeeController
{
	/**
	 * VIEW (UI shell - không load data employees nếu dùng fetch)
	 */
	public function index(): void
	{
		View::render('employee/index');
	}

	public function form($id = null): void
	{
		View::render('employee/form', compact('id'));
	}
}
