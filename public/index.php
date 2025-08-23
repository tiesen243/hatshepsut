<?php

use Framework\Application;
use Framework\Core\Env;

require_once __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

$app = new Application(__DIR__ . '/..');
$app->run();
