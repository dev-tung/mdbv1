<?php

class AdminController
{
    public function login(): void
    {
        if (Session::get('auth_user')) {
            header('Location: /admin/orders');
            exit;
        }

        View::render('admin/login', [], false);
    }

    public function logout(): void
    {
        Session::remove('auth_user');

        header('Location: /admin/login');
        exit;
    }
}