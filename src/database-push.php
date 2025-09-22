<?php

use Framework\Core\Database;
use Framework\Core\Env;

require_once __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

$config = require_once __DIR__ . '/../app/config.php';
Database::connect($config['database']);

$modelsPath = __DIR__ . '/../app/Models';
foreach (glob($modelsPath . '/*.php') as $file) {
  require_once $file;
  $className = 'App\\Models\\' . basename($file, '.php');
  $instance = new $className();
  $instance->createTable();
}
