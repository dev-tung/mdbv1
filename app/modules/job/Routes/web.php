<?php

use App\Core\Router;

use App\Job\Controllers\YonexCategoryCrawler;
use App\Job\Controllers\YonexProductCrawler;
use App\Job\Controllers\YonexProductDetailCrawler;
use App\Job\Controllers\YonexProductImporter;
use App\Job\Controllers\ShopProcedureCreator;

// =========================
// SHOP PROCEDURE
// =========================

Router::get(
	'/job/yonex-category-crawl',
	[YonexCategoryCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/yonex-product-crawl',
	[YonexProductCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/yonex-product-detail-crawl',
	[YonexProductDetailCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/yonex-product-import',
	[YonexProductImporter::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/shop-procedure-create',
	[ShopProcedureCreator::class, 'run'],
	[
		'auth' => 'admin',
	],
);
