<?php

if (!function_exists('crawl_get_html')) {

    function crawl_get_html(string $url): string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_ENCODING => '',
        ]);

        $html = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        crawl_log('HTTP CODE: ' . $statusCode);

        if ($error) {
            crawl_log('CURL WARNING: ' . $error);
        }

        return $html ?: '';
    }
}

if (!function_exists('crawl_download_image')) {

    function crawl_download_image(string $url, string $path): bool
    {
        $fp = fopen($path, 'wb');

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 120,
        ]);

        $result = curl_exec($ch);

        curl_close($ch);
        fclose($fp);

        return (bool) $result;
    }
}

if (!function_exists('crawl_delete_directory')) {

    function crawl_delete_directory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;

            if (is_dir($path)) {
                crawl_delete_directory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

}

if (!function_exists('crawl_log')) {

    function crawl_log(string $message): void
    {
        if (php_sapi_name() === 'cli') {
            echo $message . PHP_EOL;
        } else {
            echo $message . '<br>' . PHP_EOL;
        }

        @ob_flush();
        flush();
    }
}
