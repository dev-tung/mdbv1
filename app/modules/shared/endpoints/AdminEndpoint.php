<?php

class AdminEndpoint
{
    public function apiLogin()
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {

            return Response::json([
                'success' => false,
                'message' => 'Vui lòng nhập tài khoản và mật khẩu'
            ], 400);

        }

        $userRepository = new UserRepository();

        $user = $userRepository->first([
            'username' => $username
        ]);

        if (!$user) {

            return Response::json([
                'success' => false,
                'message' => 'Tài khoản không tồn tại'
            ], 401);

        }

        // TEMP LOGIN
        if ($password !== '123456') {

            return Response::json([
                'success' => false,
                'message' => 'Sai mật khẩu'
            ], 401);

        }

        Auth::login([
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => 'admin'
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data'    => Auth::user()
        ]);
    }

    public function apiLogout()
    {
        Auth::logout();

        return Response::json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }
}