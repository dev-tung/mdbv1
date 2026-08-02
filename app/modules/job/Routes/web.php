<?php

use App\Core\Router;
use App\Job\Controllers\ProcedureCreator;
use App\Job\Controllers\YonexCategoryCrawler;
use App\Job\Controllers\YonexProductCrawler;
use App\Job\Controllers\YonexProductDetailCrawler;
use App\Job\Controllers\YonexProductImporter;
use App\Job\Controllers\DucanProductCrawler;
use App\Job\Controllers\DucanProductMapper;

Router::get(
	'/job/ducan/list',
	[DucanProductMapper::class, 'list'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/ducan/matched',
	[DucanProductMapper::class, 'matched'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/ducan/unmatched',
	[DucanProductMapper::class, 'unmatched'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/ducan/crawl',
	[DucanProductCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
    '/job/ducan/update',
    [DucanProductMapper::class, 'update'],
    [
        'auth' => 'admin',
    ],
);

Router::get(
	'/job/crawl-yonex-category',
	[YonexCategoryCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/crawl-yonex-product',
	[YonexProductCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/crawl-yonex-product-detail',
	[YonexProductDetailCrawler::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/import-yonex-product',
	[YonexProductImporter::class, 'run'],
	[
		'auth' => 'admin',
	],
);

Router::get(
	'/job/procedure',
	[ProcedureCreator::class, 'run'],
	[
		'auth' => 'admin',
	],
);
