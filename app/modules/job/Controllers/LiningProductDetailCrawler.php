<?php

namespace App\Job\Controllers;

class LiningProductDetailCrawler
{
	protected string $inputFile;

	protected string $outputFile;

	protected string $imageDir;

	public function __construct()
	{
		$this->inputFile =
			PATH_ROOT .
			'/public/craw/json/lining_product.json';

		$this->outputFile =
			PATH_ROOT .
			'/public/craw/json/lining_product_detail.json';

		$this->imageDir =
			rtrim(PATH_ROOT, '/') .
			'/public/craw/image/lining_product_detail';
	}

	public function run(): void
	{
		set_time_limit(0);

		/**
		 * =========================
		 * LIVE LOG
		 * =========================
		 */

		@ini_set('output_buffering', 'off');
		@ini_set('zlib.output_compression', '0');
		@ini_set('implicit_flush', '1');

		while (ob_get_level() > 0) {
			@ob_end_flush();
		}

		ob_implicit_flush(true);

		header('Content-Type: text/html; charset=UTF-8');
		header('Cache-Control: no-cache');
		header('X-Accel-Buffering: no');

		$this->log('================================');
		$this->log('START LINING PRODUCT DETAIL CRAWLER');
		$this->log('================================');

		/**
		 * =========================
		 * PREPARE DIRECTORIES
		 * =========================
		 */

		$this->log('Preparing directories...');

		$this->ensureDir(
			dirname($this->outputFile),
		);

		$this->ensureDir(
			$this->imageDir,
		);

		/**
		 * =========================
		 * CLEAN IMAGES
		 * =========================
		 */

		$this->log('Cleaning old detail images...');

		if (is_dir($this->imageDir)) {
			crawl_delete_directory(
				$this->imageDir,
			);
		}

		$this->ensureDir(
			$this->imageDir,
		);

		$this->log('Image directory ready.');

		/**
		 * =========================
		 * LOAD INPUT
		 * =========================
		 */

		$this->log('');
		$this->log('Loading products...');

		if (!file_exists($this->inputFile)) {

			$this->log(
				'ERROR: Input file not found.',
			);

			throw new \RuntimeException(
				'Missing input file: ' .
				$this->inputFile,
			);
		}

		$products = json_decode(
			file_get_contents(
				$this->inputFile,
			),
			true,
		);

		if (!is_array($products)) {

			$this->log(
				'ERROR: Invalid input JSON.',
			);

			throw new \RuntimeException(
				'Invalid input JSON format',
			);
		}

		$products = array_values(
			array_filter(
				$products,
				function ($product) {
					if (!is_array($product)) {
						return false;
					}

					$category = strtolower((string) ($product['category'] ?? ''));

					return $category === 'vot' || $category === 'vợt';
				}
			)
		);

		$this->log(
			'Products loaded: ' .
			count($products),
		);

		$this->log('');
		$this->log('Starting fresh detail crawl...');

		$results = [];

                $this->log(
                        'Fresh crawl mode enabled.',
                );

                /**
                 * =========================
                 * CRAWL
                 * =========================
                 */

                foreach ($products as $i => $product) {

                        $this->log('');
                        $this->log(
                                '==============================',
                        );

                        $this->log(
                                'PRODUCT ' .
                                ($i + 1) .
                                '/' .
                                count($products),
                        );

                        $this->log(
                                '==============================',
                        );

                        $url =
                                $product['url'] ?? '';

                        $slug =
                                $product['slug'] ?? '';

                        if (!$url || !$slug) {

                                $this->log(
                                        'SKIP: Invalid product.',
                                );

                                continue;
                        }

                        $this->log(
                                'NAME: ' .
                                ($product['name'] ?? ''),
                        );

                        $this->log(
                                'URL: ' .
                                $url,
                        );

                /**
                 * =========================
                 * FETCH
                 * =========================
                 */


			$this->log(
				'Loading product detail...',
			);

			$res =
				$this->fetchHtml($url);

			$this->log(
				'HTTP CODE: ' .
				$res['code'],
			);

			if (
				$res['code'] !== 200 ||
				!$res['html']
			) {

				$this->log(
					'SKIP HTTP ERROR',
				);

				continue;
			}

			$this->log(
				'HTML LENGTH: ' .
				strlen($res['html']),
			);

			/**
			 * =========================
			 * PARSE
			 * =========================
			 */

			$this->log(
				'Parsing product detail...',
			);

			$detail =
				$this->parseDetail(
					$res['html'],
				);

			$this->log(
				'TITLE: ' .
				($detail['title'] ?? ''),
			);

			$this->log(
				'DESCRIPTION: ' .
				(
					$detail['description']
					? 'FOUND'
					: 'NOT FOUND'
				),
			);

			$this->log(
				'SPECS: ' .
				count(
					$detail['specs'],
				),
			);

			$this->log(
				'IMAGES FOUND: ' .
				count(
					$detail['images'],
				),
			);

			/**
			 * =========================
			 * DOWNLOAD IMAGES
			 * =========================
			 */

			$localImages =
				$this->downloadImages(
					$slug,
					$detail['images'],
				);

			$this->log(
				'IMAGES SAVED: ' .
				count($localImages),
			);

			/**
			 * =========================
			 * BUILD RESULT
			 * =========================
			 */

			$item =
				array_merge(
					$product,
					$detail,
					[
						'detail_url' =>
							$url,

						'local_images' =>
							$localImages,
					],
				);

			$results[$slug] =
				$item;

			/**
			 * =========================
			 * SAVE PROGRESS
			 * =========================
			 */

			$this->log(
				'Saving progress...',
			);

			$this->save(
				$results,
			);

			$this->log(
				'DONE: ' .
				$slug,
			);

			sleep(1);
		}

		/**
		 * =========================
		 * FINAL
		 * =========================
		 */

		$this->log('');
		$this->log('================================');
		$this->log('DONE ALL');
		$this->log(
			'TOTAL: ' .
			count($results),
		);
		$this->log(
			'OUTPUT: ' .
			$this->outputFile,
		);
		$this->log(
			'IMAGES: ' .
			$this->imageDir,
		);
		$this->log('================================');
	}

	/**
	 * =========================
	 * FETCH HTML
	 * =========================
	 */

	protected function fetchHtml(
		string $url,
	): array {

		$ch =
			curl_init($url);

		curl_setopt_array(
			$ch,
			[
				CURLOPT_RETURNTRANSFER =>
					true,

				CURLOPT_FOLLOWLOCATION =>
					true,

				CURLOPT_TIMEOUT =>
					30,

				CURLOPT_CONNECTTIMEOUT =>
					10,

				CURLOPT_USERAGENT =>
					'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/151.0 Safari/537.36',

				CURLOPT_HTTPHEADER =>
					[
						'Accept: text/html,application/xhtml+xml',
						'Accept-Language: vi,en-US;q=0.9,en;q=0.8',
					],
			],
		);

		$html =
			curl_exec($ch);

		$code =
			curl_getinfo(
				$ch,
				CURLINFO_HTTP_CODE,
			);

		$error =
			curl_error($ch);

		curl_close($ch);

		if ($error) {

			$this->log(
				'CURL ERROR: ' .
				$error,
			);
		}

		return [
			'html' =>
				$html ?: '',

			'code' =>
				$code,
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

    $dom = new \DOMDocument();
    @$dom->loadHTML($html);

    $xpath = new \DOMXPath($dom);

    $data = [
      'title' => null,
      'description' => null,
      'specs' => [],
      'images' => [],
    ];

    /**
     * =========================
     * TITLE
     * =========================
     */

    $h1 = $xpath->query('//h1')->item(0);

    if ($h1) {
      $data['title'] = trim(
        preg_replace('/\s+/', ' ', $h1->textContent)
      );
    }

    /**
     * =========================
     * DESCRIPTION
     * =========================
     */

    $desc = $xpath->query(
      '//div[contains(@class,"description")]'
    )->item(0);

    if ($desc) {
      $data['description'] = trim(
        preg_replace('/\s+/', ' ', $desc->textContent)
      );
    }

    /**
     * =========================
     * SPECS
     * =========================
     */

    foreach ($xpath->query('//table//tr') as $row) {

      $cols = $xpath->query(
        './/td|./th',
        $row
      );

      if ($cols->length < 2) {
        continue;
      }

      $key = trim(
        preg_replace('/\s+/', ' ', $cols->item(0)->textContent)
      );

      $value = trim(
        preg_replace('/\s+/', ' ', $cols->item(1)->textContent)
      );

      if ($key && $value) {
        $data['specs'][$key] = $value;
      }
    }

    /**
     * =========================
     * PRODUCT IMAGES ONLY
     * =========================
     *
     * Chỉ lấy trong:
     *
     * .product_images_list
     *
     * Không lấy ảnh ở khu vực khác.
     */

    $images = [];

    /**
     * =========================
     * 1. LARGE IMAGE
     * =========================
     */

    $largeImages = $xpath->query(
      '//div[contains(@class,"product_images_list")]'
      . '//div[contains(@class,"large-image")]'
      . '//img[@src]'
    );

    foreach ($largeImages as $img) {

      $src = trim(
        $img->getAttribute('src')
      );

      if ($src) {
        $images[] = $this->normalizeImage($src);
      }
    }

    /**
     * =========================
     * 2. THUMBNAIL DATA-ZOOM-IMAGE
     * =========================
     */

    $thumbnailNodes = $xpath->query(
      '//div[contains(@class,"product_images_list")]'
      . '//div[@id="gallery_01"]'
      . '//a[@data-zoom-image]'
    );

    foreach ($thumbnailNodes as $node) {

      $image = trim(
        $node->getAttribute('data-zoom-image')
      );

      if ($image) {
        $images[] = $this->normalizeImage($image);
      }
    }

    /**
     * =========================
     * 3. FALLBACK DATA-IMAGE
     * =========================
     */

    $thumbnailImages = $xpath->query(
      '//div[contains(@class,"product_images_list")]'
      . '//div[@id="gallery_01"]'
      . '//a[@data-image]'
    );

    foreach ($thumbnailImages as $node) {

      $image = trim(
        $node->getAttribute('data-image')
      );

      if ($image) {
        $images[] = $this->normalizeImage($image);
      }
    }

    /**
     * =========================
     * UNIQUE IMAGES
     * =========================
     */

    $data['images'] = array_values(
      array_unique(
        array_filter($images)
      )
    );

    crawl_log(
      'PRODUCT IMAGES: ' .
      count($data['images'])
    );

    foreach ($data['images'] as $i => $image) {

      crawl_log(
        'IMAGE ' .
        ($i + 1) .
        ': ' .
        $image
      );
    }

    return $data;
  }

	/**
	 * =========================
	 * DOWNLOAD IMAGES
	 * =========================
	 */

	protected function downloadImages(
		string $slug,
		array $images,
	): array {

		$dir =
			$this->imageDir .
			'/' .
			$slug;

		$this->ensureDir(
			$dir,
		);

		$saved = [];

		foreach (
			$images
			as $i => $url
		) {

			if (!$url) {
				continue;
			}

			$this->log(
				'Image ' .
				($i + 1) .
				'/' .
				count($images),
			);

			$this->log(
				'URL: ' .
				$url,
			);

			$path =
				parse_url(
					$url,
					PHP_URL_PATH,
				);

			$ext =
				pathinfo(
					$path ?? '',
					PATHINFO_EXTENSION,
				);

			$ext =
				strtolower(
					$ext ?: 'jpg',
				);

			if (
				!in_array(
					$ext,
					[
						'jpg',
						'jpeg',
						'png',
						'webp',
						'gif',
					],
					true,
				)
			) {

				$ext =
					'jpg';
			}

			$file =
				($i + 1) .
				'.' .
				$ext;

			$savePath =
				$dir .
				'/' .
				$file;

			$this->log(
				'Downloading...',
			);

			if (
				crawl_download_image(
					$url,
					$savePath,
				)
			) {

				$saved[] =
					'image/lining_product_detail/' .
					$slug .
					'/' .
					$file;

				$this->log(
					'IMAGE OK: ' .
					$file,
				);

			} else {

				$this->log(
					'IMAGE FAILED',
				);
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
		if (
			!file_exists(
				$this->outputFile,
			)
		) {

			return [];
		}

		$old =
			json_decode(
				file_get_contents(
					$this->outputFile,
				),
				true,
			);

		if (!is_array($old)) {
			return [];
		}

		$map = [];

		foreach (
			$old
			as $item
		) {

			if (
				!empty(
					$item['slug']
				)
			) {

				$map[
					$item['slug']
				] =
					$item;
			}
		}

		return $map;
	}

	/**
	 * =========================
	 * SAVE
	 * =========================
	 */

	protected function save(
		array $data,
	): void {

		file_put_contents(
			$this->outputFile,
			json_encode(
				array_values($data),
				JSON_PRETTY_PRINT |
				JSON_UNESCAPED_UNICODE |
				JSON_UNESCAPED_SLASHES,
			),
		);
	}

	/**
	 * =========================
	 * IMAGE URL CHECK
	 * =========================
	 */

	protected function isImageUrl(
		string $url,
	): bool {

		$url =
			strtolower(
				trim($url),
			);

		if (!$url) {
			return false;
		}

		if (
			str_starts_with(
				$url,
				'data:image',
			)
		) {

			return false;
		}

		return
			str_contains(
				$url,
				'.jpg',
			)
			||
			str_contains(
				$url,
				'.jpeg',
			)
			||
			str_contains(
				$url,
				'.png',
			)
			||
			str_contains(
				$url,
				'.webp',
			)
			||
			str_contains(
				$url,
				'.gif',
			)
			||
			str_contains(
				$url,
				'/image/',
			)
			||
			str_contains(
				$url,
				'/images/',
			)
			||
			str_contains(
				$url,
				'/products/',
			);
	}

	/**
	 * =========================
	 * NORMALIZE IMAGE
	 * =========================
	 */

	protected function normalizeImage(
		string $url,
	): string {

		$url =
			trim($url);

		/**
		 * Remove query string
		 */

		$url =
			strtok(
				$url,
				'?',
			);

		/**
		 * Protocol relative
		 */

		if (
			str_starts_with(
				$url,
				'//',
			)
		) {

			$url =
				'https:' .
				$url;
		}

		/**
		 * Relative URL
		 */

		elseif (
			str_starts_with(
				$url,
				'/',
			)
		) {

			$url =
				'https://liningbadminton.vn' .
				$url;
		}

		/**
		 * HTTP → HTTPS
		 */

		return str_replace(
			'http://',
			'https://',
			$url,
		);
	}

	/**
	 * =========================
	 * ENSURE DIRECTORY
	 * =========================
	 */

	protected function ensureDir(
		string $path,
	): void {

		if (!is_dir($path)) {

			mkdir(
				$path,
				0777,
				true,
			);
		}
	}

	/**
	 * =========================
	 * LIVE LOG
	 * =========================
	 */

	protected function log(
		string $message,
	): void {

		echo
			'<div style="font-family:monospace;white-space:pre-wrap;">'
			.
			htmlspecialchars(
				$message,
				ENT_QUOTES,
				'UTF-8',
			)
			.
			'</div>';

		/**
		 * Force browser flush
		 */

		echo str_repeat(
			' ',
			4096,
		);

		if (
			function_exists(
				'ob_flush',
			)
		) {

			@ob_flush();
		}

		flush();
	}
}