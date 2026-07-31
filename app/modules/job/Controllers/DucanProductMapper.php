<?php

namespace App\Job\Controllers;

use App\Core\Database;
use App\Core\View;

class DucanProductMapper
{

    public function update(): void
    {
        set_time_limit(0);

        [$ducanProducts, $productMap] = $this->getData();

        header('Content-Type: text/html; charset=utf-8');

        echo str_repeat(' ', 4096);

        $updated = 0;

        foreach ($ducanProducts as $ducan) {

            $key = $this->buildKey($ducan["name"]);

            // Chỉ cập nhật sản phẩm đã match
            if (!isset($productMap[$key])) {
                continue;
            }

            $price = (int) $ducan["price"];

            // Bỏ qua nếu không có giá
            if ($price <= 0) {
                continue;
            }

            $salePrice = $this->getSalePrice($ducan);

            Database::execute("
                UPDATE products
                SET
                    price = ?,
                    sale_price = ?,
                    updated_by = ?
                WHERE id = ?
            ", [
                $price,
                $salePrice,
                "ducan",
                $productMap[$key]["id"],
            ]);

            $updated++;

            echo sprintf(
                "%d | %s | Price: %s | Sale: %s<br>",
                $productMap[$key]["id"],
                htmlspecialchars($productMap[$key]["name"]),
                number_format($price),
                number_format($salePrice)
            );

            flush();
        }

        echo "<hr>";
        echo "Done. Updated {$updated} products.";
    }

    /**
     * Danh sách tất cả Đức An
     */
    public function list(): void
    {
        set_time_limit(0);


        $products = Database::get("
            SELECT
                id,
                name,
                price,
                sale_price,
                url
            FROM crawl_ducan_products
            ORDER BY id
        ");


        View::render("ducan/list", [

            "products" => $products,

            "total" => count($products),

        ]);
    }





    /**
     * Danh sách đã MAP
     */
    public function matched(): void
    {
        set_time_limit(0);


        [$ducanProducts, $productMap] = $this->getData();



        $matched = [];



        foreach ($ducanProducts as $ducan) {


            $key = $this->buildKey($ducan["name"]);



            if (!isset($productMap[$key])) {
                continue;
            }



            $matched[] = [

                "crawl_id" => $ducan["id"],

                "crawl_name" => $ducan["name"],

                "crawl_url" => $ducan["url"],


                "product_id" => $productMap[$key]["id"],

                "product_name" => $productMap[$key]["name"],


                "ducan_price" => $ducan["price"],

                "ducan_sale_price" => $this->getSalePrice($ducan),

            ];

        }



        View::render("ducan/matched", [

            "matched" => $matched,

            "total" => count($matched),

        ]);

    }





    /**
     * Danh sách chưa MAP
     */
    public function unmatched(): void
    {
        set_time_limit(0);


        [$ducanProducts, $productMap] = $this->getData();



        $unmatched = [];



        foreach ($ducanProducts as $ducan) {


            $key = $this->buildKey($ducan["name"]);



            if (isset($productMap[$key])) {
                continue;
            }



            $unmatched[] = [

                "crawl_id" => $ducan["id"],

                "crawl_name" => $ducan["name"],

                "crawl_url" => $ducan["url"],


                "price" => $ducan["price"],

                "sale_price" => $this->getSalePrice($ducan),

            ];

        }



        View::render("ducan/unmatched", [

            "unmatched" => $unmatched,

            "total" => count($unmatched),

        ]);

    }





    /**
     * Lấy dữ liệu chung
     */
    protected function getData(): array
    {

        $ducanProducts = Database::get("
            SELECT
                id,
                name,
                price,
                sale_price,
                url
            FROM crawl_ducan_products
            ORDER BY id
        ");



        $products = Database::get("
            SELECT
                id,
                name
            FROM products
            WHERE category_id = 1
            ORDER BY id
        ");



        $productMap = [];



        foreach ($products as $product) {


            $key = $this->buildKey($product["name"]);



            if (!$key) {
                continue;
            }



            $productMap[$key] = $product;

        }



        return [
            $ducanProducts,
            $productMap
        ];

    }





    /**
     * Lấy giá sale
     */
    protected function getSalePrice(array $product): int
    {

        if (
            empty($product["sale_price"]) ||
            $product["sale_price"] <= 0
        ) {

            return (int)$product["price"] - 10000;

        }


        return (int)$product["sale_price"];

    }





    /**
     * Chuẩn hóa tên sản phẩm
     */
    protected function buildKey(string $name): string
    {

        $name = mb_strtolower($name);


        $name = $this->removeVietnamese($name);



        $remove = [

            "vot",

            "cau long",

            "yonex",

            "chinh hang",

            "gia tot",

            "gia re",

            "chat luong",

            "ducansport",

            "duc an sport",

            "do",

            "den",

            "trang",

            "xam",

            "gray",

            "dark gray",

            "light gray",

            "viktor axelsen",

            "chen yufei",

            "huang yaqiong",

            "zheng siwei",

            "nguyen thuy linh",

        ];



        $name = str_replace(
            $remove,
            " ",
            $name
        );



        $name = preg_replace(
            "/[^a-z0-9 ]+/i",
            " ",
            $name
        );



        $name = preg_replace(
            "/\s+/",
            " ",
            $name
        );



        $name = trim($name);



        $name = preg_replace_callback(
            "/\b0+(\d+)\b/",
            function ($match) {

                return $match[1];

            },
            $name
        );



        $name = preg_replace(
            "/(\d+)\s+(zz|z|va|d|s|rx)\b/i",
            '$1$2',
            $name
        );



        $name = preg_replace(
            "/(\d+)(va)\s+(zz)/i",
            '$1$2$3',
            $name
        );



        return str_replace(
            " ",
            "-",
            $name
        );

    }





    /**
     * Bỏ dấu tiếng Việt
     */
    protected function removeVietnamese(string $str): string
    {

        $map = [

            "á"=>"a",
            "à"=>"a",
            "ả"=>"a",
            "ã"=>"a",
            "ạ"=>"a",

            "ă"=>"a",
            "â"=>"a",

            "đ"=>"d",

            "é"=>"e",
            "è"=>"e",
            "ẻ"=>"e",
            "ẽ"=>"e",
            "ẹ"=>"e",

            "í"=>"i",
            "ì"=>"i",
            "ỉ"=>"i",
            "ĩ"=>"i",
            "ị"=>"i",

            "ó"=>"o",
            "ò"=>"o",
            "ỏ"=>"o",
            "õ"=>"o",
            "ọ"=>"o",

            "ú"=>"u",
            "ù"=>"u",
            "ủ"=>"u",
            "ũ"=>"u",
            "ụ"=>"u",

            "ý"=>"y",
            "ỳ"=>"y",
            "ỷ"=>"y",
            "ỹ"=>"y",
            "ỵ"=>"y",

        ];


        return strtr($str, $map);

    }

}