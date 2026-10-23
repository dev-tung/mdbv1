<?php

namespace App\Auth\Controllers;

use App\Core\Session;
use App\Core\View;

class AdminController
{
	public function login(): void
	{
		if (Session::get('admin_user')) {
			header('Location: /admin/orders');
			exit();
		}

		View::render('admin/login', [], false);
	}

	public function logout(): void
	{
		Session::remove('admin_user');

		header('Location: /admin/login');
		exit();
	}
}
