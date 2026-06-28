<?php
class Middleware
{
    public static function handle(array $middlewares = []): void
    {
        $auth = $middlewares['auth'] ?? null;

        switch ($auth) {

            case 'admin':
                self::admin();
                break;

            case 'customer':
                self::customer();
                break;
        }
    }

    protected static function admin(): void
    {
        if (Session::get('auth_user')) {
            return;
        }

        http_response_code(401);

        echo json_encode([
            'message' => 'Unauthorized'
        ]);

        exit;
    }

    protected static function customer(): void
    {
        if (Session::get('auth_customer')) {
            return;
        }

        http_response_code(401);

        echo json_encode([
            'message' => 'Unauthorized'
        ]);

        exit;
    }
}