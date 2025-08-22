<?php

use Framework\Core\Env;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

Env::load(BASE_PATH . '/.env');

$app = require_once BASE_PATH . '/src/app.php';
$app->run();
