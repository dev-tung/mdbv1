<?php

class AdminEndpoint
{
    public function apiLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // =========================
        // TEMP LOGIN (DEV ONLY)
        // =========================
        if ($password !== '123456') {
            return Response::json([
                'success' => false,
                'message' => 'Sai mật khẩu'
            ], 401);
        }

        // giả lập user admin
        $user = [
            'id'    => 1,
            'email' => $email,
            'role'  => 'admin',
            'name'  => 'Administrator'
        ];

        // =========================
        // STORE SESSION DIRECTLY
        // =========================
        Session::set('auth_user', $user);

        return Response::json([
            'success' => true,
            'message' => 'Login success',
            'data' => $user
        ]);
    }
}