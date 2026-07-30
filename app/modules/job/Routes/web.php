<?php

use App\Core\Router;
use App\Job\Controllers\ProcedureShop;
use App\Job\Controllers\CrawlYonexCategory;
use App\Job\Controllers\CrawlYonexProduct;
use App\Job\Controllers\CrawlYonexProductDetail;
use App\Job\Controllers\ImportYonexProduct;
use App\Job\Controllers\CrawlDucan;


Router::get(
	'/job/crawl-ducan',
	[CrawlDucan::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/crawl-yonex-category',
	[CrawlYonexCategory::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/crawl-yonex-product',
	[CrawlYonexProduct::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/crawl-yonex-product-detail',
	[CrawlYonexProductDetail::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/import-yonex-product',
	[ImportYonexProduct::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/procedure-shop',
	[ProcedureShop::class, 'run'],
	[
		'auth' => 'admin',
	],
);
