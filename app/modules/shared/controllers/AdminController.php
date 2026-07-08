<?php

class AdminController
{
    public function login(): void
    {
        // $password = '123456';

        // $hash = password_hash($password, PASSWORD_DEFAULT);

        // dd($hash);

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
