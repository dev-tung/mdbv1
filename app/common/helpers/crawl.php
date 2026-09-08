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

		crawl_log("HTTP {$statusCode} | {$url}");

		if ($error) {
			crawl_log("CURL ERROR: {$error}");
		}

		return $html ?: '';
	}
}

if (!function_exists('crawl_download_image')) {
        function crawl_download_image(string $url, string $path): bool
        {
                $dir = dirname($path);

                if ($dir !== '' && $dir !== '.' && !is_dir($dir)) {
                        if (!@mkdir($dir, 0777, true) && !is_dir($dir)) {
                                crawl_log('CANNOT CREATE DIRECTORY: ' . $dir);
                                return false;
                        }
                }

                $fp = @fopen($path, 'wb');

                if ($fp === false) {
                        crawl_log('CANNOT OPEN FILE FOR WRITING: ' . $path);
                        return false;
                }

                $ch = curl_init($url);

                curl_setopt_array($ch, [
                        CURLOPT_FILE => $fp,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_USERAGENT => 'Mozilla/5.0',
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_TIMEOUT => 120,
                ]);

                $result = curl_exec($ch);
                $curlError = curl_error($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);
                fclose($fp);

                if ($result === false || $curlError !== '') {
                        @unlink($path);
                        crawl_log('CURL ERROR: ' . $curlError);
                        return false;
                }

                if ($httpCode >= 400) {
                        @unlink($path);
                        crawl_log('HTTP ERROR: ' . $httpCode . ' | ' . $url);
                        return false;
                }

                return true;
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

// if (!function_exists('crawl_log')) {
// 	function crawl_log(string $message): void
// 	{
// 		$line = '[' . date('Y-m-d H:i:s') . '] ' . $message;

// 		if (php_sapi_name() === 'cli') {
// 			echo $line . PHP_EOL;
// 			return;
// 		}

// 		error_log($line);
// 	}
// }

if (!function_exists('crawl_log')) {
	function crawl_log(string $message): void
	{
		echo $message;
	}
}