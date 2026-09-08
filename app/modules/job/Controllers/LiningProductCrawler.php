<?php

namespace App\Job\Controllers;

class LiningProductCrawler
{
	protected string $baseUrl = 'https://liningbadminton.vn/collections/vot';

	protected string $jsonFile;

	protected string $imgDir;

	public function __construct()
	{
		$this->jsonFile = PATH_ROOT . '/public/craw/json/lining_product.json';

		$this->imgDir = PATH_ROOT . '/public/craw/image/lining_product';
	}

	public function run(): void
	{
		set_time_limit(0);

		/**
		 * =========================
		 * ENABLE LIVE LOG
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

		$this->log('START Lining Product Crawler');
		$this->log('Base URL: ' . $this->baseUrl);

		/**
		 * =========================
		 * PREPARE FOLDERS
		 * =========================
		 */

		$this->log('Preparing directories...');

		$this->ensureDir($this->imgDir);
		$this->ensureDir(dirname($this->jsonFile));

		/**
		 * =========================
		 * CLEAN OLD DATA
		 * =========================
		 */

		$this->log('Cleaning old images...');

		crawl_delete_directory($this->imgDir);

		$this->ensureDir($this->imgDir);

		$this->log('Old images removed.');

		/**
		 * =========================
		 * LOAD FIRST PAGE
		 * =========================
		 */

		$this->log('');
		$this->log('Loading collection page...');

		$firstHtml = crawl_get_html($this->baseUrl);

		if (!$firstHtml) {
			$this->log('ERROR: Cannot load collection page.');

			throw new \RuntimeException(
				'Cannot load Li-Ning racket collection',
			);
		}

		$this->log(
			'Collection page loaded. HTML length: ' .
			strlen($firstHtml),
		);

		/**
		 * =========================
		 * GET PAGE URLS
		 * =========================
		 */

		$this->log('');
		$this->log('Detecting pagination...');

		$pageUrls = $this->getPageUrls(
			$firstHtml,
			$this->baseUrl,
		);

		$this->log(
			'Pages found: ' .
			count($pageUrls),
		);

		foreach ($pageUrls as $index => $url) {
			$this->log(
				'Page ' .
				($index + 1) .
				': ' .
				$url,
			);
		}

		/**
		 * =========================
		 * CRAWL PRODUCTS
		 * =========================
		 */

		$products = [];

		$this->log('');
		$this->log('START CRAWLING PRODUCTS');

		foreach ($pageUrls as $pageIndex => $url) {

			$pageNumber = $pageIndex + 1;

			$this->log('');
			$this->log('==============================');
			$this->log(
				'PAGE ' .
				$pageNumber .
				'/' .
				count($pageUrls),
			);
			$this->log($url);
			$this->log('==============================');

			/**
			 * LOAD PAGE
			 */

			$this->log('Loading page...');

			$html = crawl_get_html($url);

			if (!$html) {

				$this->log(
					'ERROR: Cannot load page.',
				);

				continue;
			}

			$this->log(
				'Page loaded. HTML length: ' .
				strlen($html),
			);

			/**
			 * PARSE PRODUCTS
			 */

			$this->log(
				'Parsing products...',
			);

			$items = $this->parseProducts($html);

			$this->log(
				'Products found on page: ' .
				count($items),
			);

			/**
			 * MERGE
			 */

			foreach ($items as $key => $item) {

				$products[$key] = $item;

				$this->log(
					'Added product: ' .
					$item['name'],
				);
			}

			$this->log(
				'Total products so far: ' .
				count($products),
			);
		}

		/**
		 * =========================
		 * SAVE JSON
		 * =========================
		 */

		$this->log('');
		$this->log('Saving JSON...');

		$json = json_encode(
			array_values($products),
			JSON_PRETTY_PRINT |
			JSON_UNESCAPED_UNICODE |
			JSON_UNESCAPED_SLASHES,
		);

		file_put_contents(
			$this->jsonFile,
			$json,
		);

		$this->log(
			'JSON saved: ' .
			$this->jsonFile,
		);

		/**
		 * =========================
		 * RESULT
		 * =========================
		 */

		$this->log('');
		$this->log('==============================');
		$this->log('DONE');
		$this->log(
			'TOTAL PRODUCTS: ' .
			count($products),
		);
		$this->log(
			'JSON: ' .
			$this->jsonFile,
		);
		$this->log(
			'IMAGES: ' .
			$this->imgDir,
		);
		$this->log('==============================');
	}

	/**
	 * =========================
	 * PARSE PRODUCTS
	 * =========================
	 */

	protected function parseProducts(string $html): array
	{
		libxml_use_internal_errors(true);

		$dom = new \DOMDocument();

		$dom->loadHTML($html);

		$xpath = new \DOMXPath($dom);

		/**
		 * =========================
		 * PRODUCT LINKS
		 * =========================
		 */

		$nodes = $xpath->query(
			'//a[contains(@href,"/products/")]',
		);

		$this->log(
			'Product links detected: ' .
			($nodes ? $nodes->length : 0),
		);

		$products = [];

		if (!$nodes) {
			return [];
		}

		foreach ($nodes as $index => $node) {

			/**
			 * =========================
			 * PRODUCT URL
			 * =========================
			 */

			$href = trim(
				$node->getAttribute('href'),
			);

			if (!$href) {

				$this->log(
					'Product #' .
					($index + 1) .
					': URL empty.',
				);

				continue;
			}

			$url = $this->normalizeUrl(
				$href,
				$this->baseUrl,
			);

			/**
			 * =========================
			 * PRODUCT NAME
			 * =========================
			 */

			$name = trim(
				preg_replace(
					'/\s+/',
					' ',
					$node->textContent,
				),
			);

			if (!$name) {

				$this->log(
					'Product #' .
					($index + 1) .
					': name empty.',
				);

				continue;
			}

			/**
			 * =========================
			 * PRODUCT CARD
			 * =========================
			 */

			$productNode = $node;

			for ($i = 0; $i < 8; $i++) {

				if (!$productNode->parentNode) {
					break;
				}

				$productNode =
					$productNode->parentNode;

				$imgNodes = $xpath->query(
					'.//img',
					$productNode,
				);

				if (
					$imgNodes &&
					$imgNodes->length > 0
				) {
					break;
				}
			}

			/**
			 * =========================
			 * GET IMAGE
			 * =========================
			 */

			$image = null;

			$imgNode = null;

			if ($productNode) {

				$imgNode = $xpath->query(
					'.//img',
					$productNode,
				)->item(0);
			}

			if ($imgNode) {

				/**
				 * Lazy load attributes
				 */

				$image =
					trim(
						$imgNode->getAttribute(
							'data-src',
						),
					)
					?:
					trim(
						$imgNode->getAttribute(
							'data-original',
						),
					)
					?:
					trim(
						$imgNode->getAttribute(
							'data-lazy-src',
						),
					)
					?:
					trim(
						$imgNode->getAttribute(
							'data-image',
						),
					)
					?:
					trim(
						$imgNode->getAttribute(
							'src',
						),
					);

				/**
				 * SRCSET
				 */

				if (!$image) {

					$srcset =
						trim(
							$imgNode->getAttribute(
								'data-srcset',
							),
						)
						?:
						trim(
							$imgNode->getAttribute(
								'srcset',
							),
						);

					if ($srcset) {

						$srcsetParts =
							explode(
								',',
								$srcset,
							);

						$firstImage =
							trim(
								$srcsetParts[0] ?? '',
							);

						if ($firstImage) {

							$firstImageParts =
								preg_split(
									'/\s+/',
									$firstImage,
								);

							$image =
								$firstImageParts[0]
								?? null;
						}
					}
				}
			}

			/**
			 * =========================
			 * NORMALIZE IMAGE
			 * =========================
			 */

			if ($image) {

				$image = trim($image);

				if (
					str_starts_with(
						$image,
						'//',
					)
				) {

					$image =
						'https:' .
						$image;

				} elseif (
					str_starts_with(
						$image,
						'/',
					)
				) {

					$image =
						'https://liningbadminton.vn' .
						$image;
				}
			}

			/**
			 * =========================
			 * LOG PRODUCT
			 * =========================
			 */

			$this->log('');
			$this->log(
				'PRODUCT: ' .
				$name,
			);

			$this->log(
				'URL: ' .
				$url,
			);

			if ($image) {

				$this->log(
					'IMAGE: ' .
					$image,
				);

			} else {

				$this->log(
					'IMAGE: NOT FOUND',
				);
			}

			/**
			 * =========================
			 * SLUG
			 * =========================
			 */

			$slug = $this->slugify($name);

			/**
			 * =========================
			 * PRODUCT DATA
			 * =========================
			 */

			$product = [

				'name' =>
					$name,

				'slug' =>
					$slug,

				'url' =>
					$url,

				'category' =>
					'vot',

				'image' =>
					$image,

				'image_file' =>
					null,
			];

			/**
			 * =========================
			 * DOWNLOAD IMAGE
			 * =========================
			 */

			if ($image) {

				$path = parse_url(
					$image,
					PHP_URL_PATH,
				);

				$ext = pathinfo(
					$path ?? '',
					PATHINFO_EXTENSION,
				);

				if (!$ext) {
					$ext = 'jpg';
				}

				$ext = strtolower($ext);

				if (
					!in_array(
						$ext,
						[
							'jpg',
							'jpeg',
							'png',
							'webp',
						],
						true,
					)
				) {
					$ext = 'jpg';
				}

				$fileName =
					$slug .
					'.' .
					$ext;

				$savePath =
					$this->imgDir .
					'/' .
					$fileName;

				$this->log(
					'Downloading image...',
				);

				$downloaded =
					crawl_download_image(
						$image,
						$savePath,
					);

				if ($downloaded) {

					$product['image_file'] =
						'image/lining_product/' .
						$fileName;

					$this->log(
						'IMAGE OK: ' .
						$fileName,
					);

				} else {

					$this->log(
						'IMAGE FAILED',
					);
				}

			} else {

				$this->log(
					'Skipping image download.',
				);
			}

			/**
			 * =========================
			 * UNIQUE PRODUCT
			 * =========================
			 */

			$products[$url] = $product;

			$this->log(
				'PRODUCT DONE',
			);
		}

		return $products;
	}

	/**
	 * =========================
	 * PAGINATION
	 * =========================
	 */

	protected function getPageUrls(
		string $html,
		string $baseUrl,
	): array {

		libxml_use_internal_errors(true);

		$dom = new \DOMDocument();

		$dom->loadHTML($html);

		$xpath = new \DOMXPath($dom);

		/**
		 * =========================
		 * GET PAGE NUMBERS
		 * =========================
		 */

		$pages = [1];

		$nodes = $xpath->query(
			'//*[@onclick]',
		);

		$this->log(
			'Pagination onclick nodes: ' .
			($nodes ? $nodes->length : 0),
		);

		if ($nodes) {

			foreach ($nodes as $node) {

				$onclick =
					trim(
						$node->getAttribute(
							'onclick',
						),
					);

				if (
					preg_match_all(
						'/doSearch\s*\(\s*(\d+)\s*\)/i',
						$onclick,
						$matches,
					)
				) {

					foreach ($matches[1] as $page) {

						$page = (int) $page;

						if ($page >= 1) {

							$pages[] = $page;

							$this->log(
								'Found page number: ' .
								$page,
							);
						}
					}
				}
			}
		}

		/**
		 * =========================
		 * UNIQUE + SORT
		 * =========================
		 */

		$pages =
			array_values(
				array_unique($pages),
			);

		sort($pages);

		$this->log(
			'Page numbers: ' .
			implode(', ', $pages),
		);

		/**
		 * =========================
		 * BUILD URLS
		 * =========================
		 */

		$urls = [];

		$baseParts =
			parse_url($baseUrl);

		$query = [];

		if (!empty($baseParts['query'])) {

			parse_str(
				$baseParts['query'],
				$query,
			);
		}

		foreach ($pages as $page) {

			/**
			 * Page 1:
			 *
			 * /collections/vot
			 *
			 * Các page sau:
			 *
			 * /collections/vot?page=2&view=grid
			 */

			if ($page === 1) {

				$url =
					$this->baseUrl;

			} else {

				$pageQuery = $query;

				$pageQuery['page'] =
					$page;

				$pageQuery['view'] =
					'grid';

				$url =
					$this->baseUrl .
					'?' .
					http_build_query(
						$pageQuery,
						'',
						'&',
						PHP_QUERY_RFC3986,
					);
			}

			$urls[] = $url;

			$this->log(
				'Pagination URL: ' .
				$url,
			);
		}

		return array_values(
			array_unique($urls),
		);
	}

	/**
	 * =========================
	 * SLUGIFY
	 * =========================
	 */

	protected function slugify(
		string $text,
	): string {

		$text =
			strtolower(
				trim($text),
			);

		$text =
			iconv(
				'UTF-8',
				'ASCII//TRANSLIT//IGNORE',
				$text,
			);

		$text =
			preg_replace(
				'/[^a-z0-9]+/',
				'-',
				$text,
			);

		$text =
			preg_replace(
				'/-+/',
				'-',
				$text,
			);

		return trim(
			$text,
			'-',
		);
	}

	/**
	 * =========================
	 * NORMALIZE URL
	 * =========================
	 */

	protected function normalizeUrl(
		string $href,
		string $baseUrl,
	): string {

		if (
			str_starts_with(
				$href,
				'http://',
			)
			||
			str_starts_with(
				$href,
				'https://',
			)
		) {

			return $href;
		}

		if (
			str_starts_with(
				$href,
				'//',
			)
		) {

			return 'https:' .
				$href;
		}

		if (
			str_starts_with(
				$href,
				'/',
			)
		) {

			return
				'https://liningbadminton.vn' .
				$href;
		}

		return rtrim(
			$baseUrl,
			'/',
		) .
			'/' .
			ltrim(
				$href,
				'/',
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

		echo str_repeat(
			' ',
			4096,
		);

		if (function_exists('ob_flush')) {
			@ob_flush();
		}

		flush();
	}
}