<?php

class View
{
    protected static string $module = 'website';

    public static function setModule(string $module): void
    {
        self::$module = $module;
    }

    public static function module(): string
    {
        return self::$module;
    }

    public static function render(string $view, array $data = [], $menu = true): void
    {
        $header  = self::getHeader();
        $content = self::getContent($view);
        $footer  = self::getFooter();

        if (!file_exists($header)) {
            self::fail("Header not found: {$header}");
            return;
        }

        if (!file_exists($content)) {
            self::fail("View not found: {$content}");
            return;
        }

        if (!file_exists($footer)) {
            self::fail("Footer not found: {$footer}");
            return;
        }

        extract($data);

        require $header;
        require $content;
        require $footer;
    }

    protected static function getContent(string $view): string
    {
        return BASE_PATH
            . "/app/modules/"
            . self::$module
            . "/views/"
            . $view
            . ".php";
    }

    protected static function getLayout(): string
    {
        return self::$module === 'website' ? 'website' : 'admin';
    }

    protected static function getHeader(): string
    {
        $layout = self::getLayout();

        return BASE_PATH . "/app/common/layouts/{$layout}/header.php";
    }

    protected static function getFooter(): string
    {
        $layout = self::getLayout();

        return BASE_PATH . "/app/common/layouts/{$layout}/footer.php";
    }

    protected static function fail(string $message): void
    {
        http_response_code(500);
        echo "<pre>{$message}</pre>";
    }
}