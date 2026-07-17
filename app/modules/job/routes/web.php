<?php

// =========================
// SHOP PROCEDURE
// =========================

Router::get('/job/yonex-category-crawl', 'YonexCategoryCrawler@run', [
	'auth' => 'admin',
]);
Router::get('/job/yonex-product-crawl', 'YonexProductCrawler@run', [
	'auth' => 'admin',
]);
Router::get('/job/yonex-product-detail-crawl', 'YonexProductDetailCrawler@run', [
	'auth' => 'admin',
]);
Router::get('/job/yonex-product-import', 'YonexProductImporter@run', [
	'auth' => 'admin',
]);
Router::get('/job/shop-procedure-create', 'ShopProcedureCreator@run', [
	'auth' => 'admin',
]);
