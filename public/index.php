<?php

define('PATH_ROOT', dirname(__DIR__));

define('PATH_APP', PATH_ROOT . '/app');

define('PATH_CORE', PATH_APP . '/core');

define('PATH_MODULES', PATH_APP . '/modules');

define('PATH_VIEW', PATH_MODULES . '/website/views');

define('PATH_PUBLIC', PATH_ROOT . '/public');

require_once PATH_ROOT . '/app/core/Autoload.php';

Autoload::register();

Env::init();

define('BASE_URL', Env::get('APP_URL', 'http://localhost:8000'));

$app = new App();
$app->run();
