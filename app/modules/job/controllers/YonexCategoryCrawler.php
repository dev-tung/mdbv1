<?php

class YonexCategoryCrawler
{
    protected string $baseUrl = 'https://www.yonex.com/badminton';

    protected string $jsonFile;
    protected string $imgDir;

    public function __construct()
    {
        $this->jsonFile = PATH_ROOT . '/public/craw/json/yonex_category.json';
        $this->imgDir   = PATH_ROOT . '/public/craw/image/yonex_category';
    }

    public function run(): void
    {
        set_time_limit(0);

        /**
         * ENSURE FOLDERS
         */
        $this->ensureDir($this->imgDir);
        $this->ensureDir(dirname($this->jsonFile));

        /**
         * CLEAN OLD DATA
         */
        crawl_delete_directory($this->imgDir);

        $this->ensureDir($this->imgDir);

        crawl_log("Loading homepage...");

        $html = crawl_get_html($this->baseUrl);

        if (!$html) {
            throw new RuntimeException("Cannot load Yonex website");
        }

        /**
         * PARSE CATEGORIES
         */
        $categories = $this->extractCategories($html);

        crawl_log("Found " . count($categories) . " categories");

        /**
         * GET MENU IMAGES
         */
        preg_match_all(
            '#https://www\.yonex\.com/media/wysiwyg/submenu-icons/[^"\']+#i',
            $html,
            $matches
        );

        $images = array_values(array_unique($matches[0] ?? []));

        crawl_log("Found " . count($images) . " menu images");

        /**
         * MAP CATEGORY → KEYWORD
         */
        $map = [
            'racquets'           => 'racket',
            'strings'            => 'string',
            'stringing-machines' => 'machine',
            'shuttlecocks'       => 'shuttle',
            'shoes'              => 'shoe',
            'footwear'           => 'shoe',
            'bags'               => 'bag',
            'apparel'            => 'apparel',
            'accessories'        => 'accessory',
        ];

        /**
         * ATTACH IMAGES
         */
        foreach ($categories as $key => $category) {

            $keyword = $map[$category['slug']] ?? null;

            if (!$keyword) {
                continue;
            }

            $matchedImage = null;

            foreach ($images as $imageUrl) {

                if (stripos($imageUrl, $keyword) !== false) {
                    $matchedImage = $imageUrl;
                    break;
                }
            }

            if (!$matchedImage) {
                continue;
            }

            $fileName = $category['slug'] . '.png';
            $savePath = $this->imgDir . '/' . $fileName;

            crawl_log("Downloading: $fileName");

            if (crawl_download_image($matchedImage, $savePath)) {

                $categories[$key]['image'] = $matchedImage;
                $categories[$key]['image_file'] =
                    'image/yonex_category/' . $fileName;
            }
        }

        /**
         * SAVE JSON (SAFE)
         */
        file_put_contents(
            $this->jsonFile,
            json_encode(
                array_values($categories),
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );

        /**
         * LOG RESULT
         */
        crawl_log("====================");
        crawl_log("DONE");
        crawl_log("Categories: " . count($categories));
        crawl_log("JSON: " . $this->jsonFile);
        crawl_log("Images: " . $this->imgDir);
        crawl_log("====================");
    }

    /**
     * ENSURE DIRECTORY EXISTS
     */
    protected function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * PARSE CATEGORIES
     */
    protected function extractCategories(string $html): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $categories = [];

        $nodes = $xpath->query('//a[@href]');

        foreach ($nodes as $node) {

            $href = trim($node->getAttribute('href'));

            if (!str_contains($href, '/badminton/')) {
                continue;
            }

            if (
                !preg_match(
                    '#/badminton/(racquets|strings|stringing-machines|shuttlecocks|apparel|shoes|footwear|bags|accessories)#i',
                    $href
                )
            ) {
                continue;
            }

            $url = str_starts_with($href, 'http')
                ? $href
                : 'https://www.yonex.com' . $href;

            $path = parse_url($url, PHP_URL_PATH);
            $slug = basename(trim($path, '/'));

            if (isset($categories[$slug])) {
                continue;
            }

            $name = trim($node->textContent);

            if (!$name) {
                $name = ucwords(str_replace('-', ' ', $slug));
            }

            $categories[$slug] = [
                'name'       => $name,
                'slug'       => $slug,
                'url'        => $url,
                'image'      => null,
                'image_file' => null,
            ];
        }

        return $categories;
    }
}