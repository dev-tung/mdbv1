<?php

class Response
{
    public static function json(
        mixed $data,
        int $status = 200,
    ): void {
        http_response_code($status);

        header(
            'Content-Type: application/json; charset=utf-8',
        );

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES,
        );

        exit;
    }

    public static function success(
        mixed $data = [],
        string $message = 'Success',
    ): void {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function error(
        string $message = 'Error',
        int $status = 400,
    ): void {
        self::json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    public static function redirect(
        string $url,
    ): void {
        header(
            "Location: {$url}",
        );

        exit;
    }

    public static function back(): void
    {
        $url = $_SERVER['HTTP_REFERER']
            ?? '/';

        self::redirect($url);
    }

    public static function abort(
        int $code = 404,
        string $message = '',
    ): void {
        http_response_code($code);

        if ($message) {
            echo $message;
        }

        exit;
    }

    public static function download(
        string $file,
        ?string $filename = null,
    ): void {
        if (!file_exists($file)) {
            self::abort(
                404,
                'File not found',
            );
        }

        $filename ??=
            basename($file);

        header(
            'Content-Type: application/octet-stream',
        );

        header(
            'Content-Disposition: attachment; filename="'
            . $filename
            . '"',
        );

        readfile($file);

        exit;
    }

    public static function view(
        string $view,
        array $data = [],
    ): void {
        View::render(
            $view,
            $data,
        );
    }
}
