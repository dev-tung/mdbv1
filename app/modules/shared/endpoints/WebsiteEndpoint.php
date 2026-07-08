<?php

class WebsiteEndpoint
{
    public function apiLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($password !== '123456') {
            return Response::json(
                [
                    'success' => false,
                    'message' => 'Sai mật khẩu',
                ],
                401,
            );
        }

        $customer = [
            'id' => 1,
            'email' => $email,
            'name' => 'Customer',
        ];

        Session::set('auth_customer', $customer);

        return Response::json([
            'success' => true,
            'message' => 'Login success',
            'data' => $customer,
        ]);
    }
}
