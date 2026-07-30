<?php

namespace App\Job\Controllers;

use App\Core\Database;

class DucanProductCrawler
{
    protected string $baseUrl = "https://ducansport.vn/vot-cau-long-yonex";


    public function run(): void
    {
        set_time_limit(0);


        /**
         * ENABLE REALTIME OUTPUT
         */
        ini_set('output_buffering', 'off');

        ini_set('zlib.output_compression', false);

        while (ob_get_level()) {
            ob_end_flush();
        }

        ob_implicit_flush(true);



        $this->screenLog("START CRAWL ĐỨC AN");



        /**
         * LOAD FIRST PAGE
         */
        $html = crawl_get_html(
            $this->baseUrl . "?page=1&catId=0"
        );


        if (!$html) {

            throw new \RuntimeException(
                "Cannot load website."
            );

        }


        libxml_use_internal_errors(true);



        $dom = new \DOMDocument();

        $dom->loadHTML($html);


        $xPath = new \DOMXPath($dom);



        /**
         * GET TOTAL PAGE
         */
        $totalPages = 1;


        $pageNodes = $xPath->query(
            '//ul[contains(@class,"pagination")]//a[@href]'
        );



        foreach ($pageNodes as $node) {


            $href = $node->getAttribute("href");


            if (
                preg_match(
                    "/page=(\d+)/i",
                    $href,
                    $match
                )
            ) {

                $totalPages = max(
                    $totalPages,
                    (int)$match[1]
                );

            }

        }



        $this->screenLog(
            "TOTAL PAGE: {$totalPages}"
        );




        /**
         * CLEAN OLD DATA
         */
        Database::execute(
            "TRUNCATE TABLE crawl_ducan_products"
        );


        $products = [];





        /**
         * CRAWL
         */
        for (
            $page = 1;
            $page <= $totalPages;
            $page++
        ) {



            $this->screenLog(
                "CRAWL PAGE {$page}/{$totalPages}"
            );



            $url =
                "{$this->baseUrl}?page={$page}&catId=0";



            $html = crawl_get_html($url);



            if (!$html) {

                $this->screenLog(
                    "LOAD FAIL PAGE {$page}"
                );

                continue;

            }



            $dom = new \DOMDocument();

            $dom->loadHTML($html);


            $xPath = new \DOMXPath($dom);




            /**
             * PRODUCT ITEM
             */
            $nodes = $xPath->query(
                '//div[contains(@class,"product")]'
            );



            $this->screenLog(
                "FOUND PRODUCT: {$nodes->length}"
            );





            foreach ($nodes as $node) {



                /**
                 * NAME
                 */
                $nameNode = $xPath
                    ->query(
                        './/*[contains(@class,"name")]',
                        $node
                    )
                    ->item(0);



                $name = $nameNode
                    ? trim($nameNode->textContent)
                    : "";



                if (!$name) {
                    continue;
                }




                /**
                 * PRICE
                 */
                $price = $this->getPrice(
                    $xPath,
                    $node,
                    $name
                );




                /**
                 * SALE PRICE
                 */
                $salePrice =
                    $price > 10000
                        ? $price - 10000
                        : $price;





                /**
                 * URL
                 */
                $linkNode = $xPath
                    ->query(
                        ".//a[@href]",
                        $node
                    )
                    ->item(0);



                $productUrl = "";



                if ($linkNode) {


                    $productUrl =
                        trim(
                            $linkNode->getAttribute("href")
                        );



                    if (
                        $productUrl &&
                        !str_starts_with(
                            $productUrl,
                            "http"
                        )
                    ) {

                        $productUrl =
                            "https://ducansport.vn"
                            . $productUrl;

                    }

                }




                $slug =
                    basename(
                        parse_url(
                            $productUrl,
                            PHP_URL_PATH
                        )
                    );





                /**
                 * INSERT
                 */
                Database::execute(
                    "
                    INSERT IGNORE INTO crawl_ducan_products
                    (
                        name,
                        slug,
                        url,
                        price,
                        sale_price
                    )
                    VALUES
                    (
                        :name,
                        :slug,
                        :url,
                        :price,
                        :sale_price
                    )
                    ",
                    [

                        "name" => $name,

                        "slug" => $slug,

                        "url" => $productUrl,

                        "price" => $price,

                        "sale_price" => $salePrice,

                    ]
                );




                $products[] = [

                    "name" => $name,

                    "slug" => $slug,

                    "url" => $productUrl,

                    "price" => $price,

                    "sale_price" => $salePrice,

                ];




                $this->screenLog(
                    "INSERT: {$name} | PRICE: "
                    . number_format($price)
                );


            }


        }




        $this->screenLog(
            "======================"
        );


        $this->screenLog(
            "CRAWL DONE"
        );


        $this->screenLog(
            "TOTAL PRODUCT: "
            . count($products)
        );



    }





    /**
     * LOG REALTIME
     */
    protected function screenLog(
        string $message
    ): void {

        echo $message . "<br>";


        if (ob_get_level()) {

            ob_flush();

        }


        flush();

    }





    /**
     * LẤY GIÁ
     */
    protected function getPrice(
        \DOMXPath $xPath,
        \DOMElement $node,
        string $name
    ): int {


        $text = trim(
            $node->textContent
        );



        preg_match_all(
            "/\d{1,3}([.,]\d{3})+/u",
            $text,
            $matches
        );



        if (!empty($matches[0])) {


            $prices = [];



            foreach ($matches[0] as $value) {


                $number =
                    (int)preg_replace(
                        "/[^\d]/",
                        "",
                        $value
                    );



                if (
                    $number >= 50000 &&
                    $number <= 50000000
                ) {

                    $prices[] = $number;

                }


            }



            if (!empty($prices)) {

                return max($prices);

            }


        }




        $this->screenLog(
            "PRICE NOT FOUND: {$name}"
        );


        return 0;

    }

}