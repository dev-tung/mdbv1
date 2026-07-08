<?php

class YonexProductCrawler
{
    protected string $categoryFile;

    protected string $jsonFile;

    protected string $imgDir;

    public function __construct()
    {
        $this->categoryFile = PATH_ROOT . '/public/craw/json/yonex_category.json';
        $this->jsonFile = PATH_ROOT . '/public/craw/json/yonex_product.json';
        $this->imgDir = PATH_ROOT . '/public/craw/image/yonex_product';
    }

    public function run(): void
    {
        set_time_limit(0);

        /**
         * PREPARE FOLDERS
         */
        $this->ensureDir($this->imgDir);
        $this->ensureDir(dirname($this->jsonFile));

        crawl_delete_directory($this->imgDir);
        $this->ensureDir($this->imgDir);

        crawl_log('Loading categories...');

        $categories = json_decode(file_get_contents($this->categoryFile), true);

        if (!is_array($categories)) {
            throw new RuntimeException('Invalid category file');
        }

        $products = [];

        foreach ($categories as $category) {

            crawl_log('');
            crawl_log('====================');
            crawl_log('Category: ' . $category['name']);

            $items = $this->crawlCategory($category);

            foreach ($items as $key => $item) {
                $products[$key] = $item;
            }

            crawl_log('Found: ' . count($items));
        }

        /**
         * SAVE JSON SAFE
         */
        file_put_contents(
            $this->jsonFile,
            json_encode(
                array_values($products),
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES,
            ),
        );

        crawl_log('');
        crawl_log('====================');
        crawl_log('DONE');
        crawl_log('TOTAL PRODUCTS: ' . count($products));
        crawl_log('====================');
    }

    /**
     * =========================
     * CATEGORY CRAWL
     * =========================
     */
    protected function crawlCategory(array $category): array
    {
        $firstHtml = crawl_get_html($category['url']);

        if (!$firstHtml) {
            return [];
        }

        $pageUrls = $this->getPageUrls($firstHtml, $category['url']);

        crawl_log('Pages found: ' . count($pageUrls));

        $products = [];

        foreach ($pageUrls as $url) {

            crawl_log("Crawling: $url");

            $html = crawl_get_html($url);

            if (!$html) {
                continue;
            }

            $items = $this->parseProducts($html, $category);

            foreach ($items as $key => $item) {
                $products[$key] = $item;
            }
        }

        return $products;
    }

    /**
     * =========================
     * PARSE PRODUCTS
     * =========================
     */
    protected function parseProducts(string $html, array $category): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $nodes = $xpath->query('//li[contains(@class,"product-item")]');

        $products = [];

        $categoryImgDir = $this->imgDir . '/' . $category['slug'];

        $this->ensureDir($categoryImgDir);

        foreach ($nodes as $node) {

            $linkNode = $xpath->query(
                './/a[contains(@class,"product-item-link")]',
                $node,
            )->item(0);

            if (!$linkNode) {
                continue;
            }

            $name = trim(preg_replace('/\s+/', ' ', $linkNode->textContent));
            $url = trim($linkNode->getAttribute('href'));

            if (!$name || !$url) {
                continue;
            }

            $imgNode = $xpath->query('.//img', $node)->item(0);

            $image = null;

            if ($imgNode) {
                $image = $imgNode->getAttribute('src')
                    ?: $imgNode->getAttribute('data-src')
                    ?: $imgNode->getAttribute('data-original');
            }

            $slug = $this->slugify($name);

            $product = [
                'name' => $name,
                'slug' => $slug,
                'url' => $url,
                'category' => $category['slug'],
                'image' => $image,
                'image_file' => null,
            ];

            /**
             * DOWNLOAD IMAGE
             */
            if ($image) {

                $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
                if (!$ext) {
                    $ext = 'jpg';
                }

                $fileName = $slug . '.' . $ext;
                $savePath = $categoryImgDir . '/' . $fileName;

                crawl_log("Downloading: $name");

                if (crawl_download_image($image, $savePath)) {

                    $product['image_file']
                        = 'image/yonex_product/'
                        . $category['slug'] . '/'
                        . $fileName;
                }
            }

            $products[$url] = $product;
        }

        return $products;
    }

    /**
     * =========================
     * PAGINATION URLS
     * =========================
     */
    protected function getPageUrls(string $html, string $baseUrl): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $nodes = $xpath->query('//div[contains(@class,"pages")]//a[contains(@class,"page")]');

        $urls = [];

        foreach ($nodes as $node) {

            $href = trim($node->getAttribute('href'));

            if ($href) {
                $urls[] = $this->normalizeUrl($href, $baseUrl);
            }
        }

        // luôn thêm page 1
        $urls[] = $baseUrl;

        return array_values(array_unique($urls));
    }

    /**
     * =========================
     * HELPERS
     * =========================
     */
    protected function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);

        return trim($text, '-');
    }

    protected function normalizeUrl(string $href, string $baseUrl): string
    {
        if (str_starts_with($href, 'http')) {
            return $href;
        }

        return rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
    }

    protected function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
