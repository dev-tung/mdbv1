<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/core/Autoload.php';

Autoload::register();

Env::init();

define('BASE_URL', Env::get('APP_URL', 'http://localhost:8000'));

$app = new App();
$app->run();
