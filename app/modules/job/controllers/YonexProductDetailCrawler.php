<?php

class YonexProductDetailCrawler
{
	protected string $inputFile;

	protected string $outputFile;

	protected string $imageDir;

	public function __construct()
	{
		$this->inputFile = PATH_ROOT . '/public/craw/json/yonex_product.json';
		$this->outputFile = PATH_ROOT . '/public/craw/json/yonex_product_detail.json';
		$this->imageDir = rtrim(PATH_ROOT, '/') . '/public/craw/image/yonex_product_detail';
	}

	public function run(): void
	{
		set_time_limit(0);

		crawl_log('START CRAWL PRODUCT DETAIL');

		/**
		 * ENSURE OUTPUT DIR
		 */
		$this->ensureDir(dirname($this->outputFile));
		$this->ensureDir($this->imageDir);

		/**
		 * CLEAN IMAGE DIR
		 */
		if (is_dir($this->imageDir)) {
			crawl_delete_directory($this->imageDir);
		}

		$this->ensureDir($this->imageDir);

		/**
		 * LOAD INPUT PRODUCTS
		 */
		if (!file_exists($this->inputFile)) {
			throw new RuntimeException('Missing input file: ' . $this->inputFile);
		}

		$products = json_decode(file_get_contents($this->inputFile), true);

		if (!is_array($products)) {
			throw new RuntimeException('Invalid input JSON format');
		}

		/**
		 * RESUME CACHE
		 */
		$results = $this->loadResume();

		foreach ($products as $i => $product) {
			$url = $product['url'] ?? '';
			$slug = $product['slug'] ?? '';

			if (!$url || !$slug) {
				crawl_log("[$i] SKIP INVALID PRODUCT");
				continue;
			}

			/**
			 * SKIP EXISTING
			 */
			if (isset($results[$slug])) {
				crawl_log("[$i] SKIP EXISTS: $slug");
				continue;
			}

			crawl_log("[$i] Crawling: $url");

			$res = $this->fetchHtml($url);

			if ($res['code'] !== 200 || !$res['html']) {
				crawl_log('SKIP HTTP ERROR: ' . $res['code']);
				continue;
			}

			$detail = $this->parseDetail($res['html']);

			crawl_log('IMAGES FOUND: ' . count($detail['images']));

			$localImages = $this->downloadImages($slug, $detail['images']);

			$item = array_merge($product, $detail, [
				'detail_url' => $url,
				'local_images' => $localImages,
			]);

			$results[$slug] = $item;

			/**
			 * SAVE PROGRESS (SAFE)
			 */
			$this->save($results);

			crawl_log("DONE: $slug");

			sleep(1);
		}

		crawl_log('================================');
		crawl_log('DONE ALL');
		crawl_log('TOTAL: ' . count($results));
		crawl_log('OUTPUT: ' . $this->outputFile);
		crawl_log('================================');
	}

	/**
	 * =========================
	 * FETCH HTML
	 * =========================
	 */
	protected function fetchHtml(string $url): array
	{
		$ch = curl_init($url);

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_TIMEOUT => 20,
			CURLOPT_USERAGENT => 'Mozilla/5.0',
		]);

		$html = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return [
			'html' => $html ?: '',
			'code' => $code,
		];
	}

	/**
	 * =========================
	 * PARSE DETAIL
	 * =========================
	 */
	protected function parseDetail(string $html): array
	{
		libxml_use_internal_errors(true);

		$dom = new DOMDocument();
		@$dom->loadHTML($html);

		$xpath = new DOMXPath($dom);

		$data = [
			'title' => null,
			'description' => null,
			'specs' => [],
			'images' => [],
		];

		$h1 = $xpath->query('//h1')->item(0);
		if ($h1) {
			$data['title'] = trim($h1->textContent);
		}

		$desc = $xpath->query('//div[contains(@class,"description")]')->item(0);
		if ($desc) {
			$data['description'] = trim($desc->textContent);
		}

		foreach ($xpath->query('//table//tr') as $row) {
			$cols = $xpath->query('.//td|./th', $row);

			if ($cols->length < 2) {
				continue;
			}

			$k = trim($cols->item(0)->textContent);
			$v = trim($cols->item(1)->textContent);

			if ($k && $v) {
				$data['specs'][$k] = $v;
			}
		}

		/**
		 * IMAGE EXTRACTION (SAFE)
		 */
		$images = [];

		if (preg_match('#"data"\s*:\s*(\[[\s\S]*?\])#', $html, $m)) {
			$json = json_decode($m[1], true);

			if (is_array($json)) {
				foreach ($json as $item) {
					if (!empty($item['img'])) {
						$images[] = $this->normalizeImage($item['img']);
					}
					if (!empty($item['full'])) {
						$images[] = $this->normalizeImage($item['full']);
					}
				}
			}
		}

		if (empty($images)) {
			$dom2 = new DOMDocument();
			@$dom2->loadHTML($html);
			$xpath2 = new DOMXPath($dom2);

			$frames = $xpath2->query('//div[contains(@class,"fotorama__stage__frame")]');

			foreach ($frames as $frame) {
				$href = $frame->getAttribute('href');
				if ($href) {
					$images[] = $this->normalizeImage($href);
				}

				$img = $xpath2->query('.//img', $frame)->item(0);
				if ($img) {
					$src = $img->getAttribute('src');
					if ($src) {
						$images[] = $this->normalizeImage($src);
					}
				}
			}
		}

		if (empty($images)) {
			$dom3 = new DOMDocument();
			@$dom3->loadHTML($html);
			$xpath3 = new DOMXPath($dom3);

			$imgNodes = $xpath3->query('//img[contains(@src,"/media/catalog/product/")]');

			foreach ($imgNodes as $img) {
				$images[] = $this->normalizeImage($img->getAttribute('src'));
			}
		}

		$data['images'] = array_values(array_unique(array_filter($images)));

		return $data;
	}

	/**
	 * =========================
	 * DOWNLOAD IMAGES
	 * =========================
	 */
	protected function downloadImages(string $slug, array $images): array
	{
		$dir = $this->imageDir . '/' . $slug;
		$this->ensureDir($dir);

		$saved = [];

		foreach ($images as $i => $url) {
			if (!$url) {
				continue;
			}

			$ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
			if (!$ext) {
				$ext = 'jpg';
			}

			$file = $i + 1 . '.' . $ext;
			$path = $dir . '/' . $file;

			crawl_log("Downloading: $url");

			if (crawl_download_image($url, $path)) {
				$saved[] = 'image/yonex_product_detail/' . $slug . '/' . $file;
			}
		}

		return $saved;
	}

	/**
	 * =========================
	 * RESUME LOAD
	 * =========================
	 */
	protected function loadResume(): array
	{
		if (!file_exists($this->outputFile)) {
			return [];
		}

		$old = json_decode(file_get_contents($this->outputFile), true);

		if (!is_array($old)) {
			return [];
		}

		$map = [];

		foreach ($old as $item) {
			if (!empty($item['slug'])) {
				$map[$item['slug']] = $item;
			}
		}

		return $map;
	}

	/**
	 * =========================
	 * SAVE
	 * =========================
	 */
	protected function save(array $data): void
	{
		file_put_contents(
			$this->outputFile,
			json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
		);
	}

	/**
	 * =========================
	 * HELPERS
	 * =========================
	 */
	protected function ensureDir(string $path): void
	{
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
	}

	protected function normalizeImage(string $url): string
	{
		$url = trim($url);
		$url = strtok($url, '?');
		return str_replace('http://', 'https://', $url);
	}
}
