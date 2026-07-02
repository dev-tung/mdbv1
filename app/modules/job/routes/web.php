<?php

// =========================
// JOB
// =========================

Router::get('/job/yonex-category-crawl', 'YonexCategoryCrawler@run', ['auth' => 'admin']);
Router::get('/job/yonex-product-crawl', 'YonexProductCrawler@run', ['auth' => 'admin']);
Router::get('/job/yonex-product-detail-crawl', 'YonexProductDetailCrawler@run', ['auth' => 'admin']);
Router::get('/job/yonex-product-import', 'YonexProductImporter@run', ['auth' => 'admin']);
