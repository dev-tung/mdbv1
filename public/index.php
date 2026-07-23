<?php

use App\Core\App;
use App\Core\Autoload;
use App\Core\Env;

define('PATH_ROOT', dirname(__DIR__));

define('PATH_APP', PATH_ROOT . '/app');
define('PATH_CORE', PATH_APP . '/core');
define('PATH_MODULES', PATH_APP . '/modules');

define('PATH_VIEW', PATH_MODULES . '/views');
define('PATH_PUBLIC', PATH_ROOT . '/public');

require_once PATH_CORE . '/Autoload.php';

Autoload::register();

Env::init();

define('BASE_URL', Env::get('APP_URL', 'http://localhost:8000'));

$app = new App();

$app->run();