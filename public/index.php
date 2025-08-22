<?php

use Framework\Application;
use Framework\Core\Env;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

Env::load(BASE_PATH . '/.env');

$app = new Application(BASE_PATH);
$app->run();
