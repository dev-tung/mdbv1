<?php

use App\Core\Router;

use App\Shop\Jobs\YonexCategoryCrawler;
use App\Shop\Jobs\YonexProductCrawler;
use App\Shop\Jobs\YonexProductDetailCrawler;
use App\Shop\Jobs\YonexProductImporter;
use App\Shop\Jobs\ShopProcedureCreator;

// =========================
// SHOP PROCEDURE
// =========================

Router::get('/job/yonex-category-crawl', [YonexCategoryCrawler::class, 'run'], [
	'auth' => 'admin',
]);

Router::get('/job/yonex-product-crawl', [YonexProductCrawler::class, 'run'], [
	'auth' => 'admin',
]);

Router::get('/job/yonex-product-detail-crawl', [YonexProductDetailCrawler::class, 'run'], [
	'auth' => 'admin',
]);

Router::get('/job/yonex-product-import', [YonexProductImporter::class, 'run'], [
	'auth' => 'admin',
]);

Router::get('/job/shop-procedure-create', [ShopProcedureCreator::class, 'run'], [
	'auth' => 'admin',
]);