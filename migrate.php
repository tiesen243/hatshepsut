<?php

use Framework\Core\Database;
use Framework\Core\Model;

require_once __DIR__.'/vendor/autoload.php';

$databaseConfig = require_once __DIR__.'/config/database.php';

$db = Database::connect($databaseConfig);
Model::setDatabase($db);

foreach (glob(__DIR__.'/app/Models/*.php') as $modelFile) {
  require_once $modelFile;
}

$declared = get_declared_classes();

foreach ($declared as $class) {
  if (is_subclass_of($class, 'Framework\Core\Model') && method_exists($class, 'migrate'))
    $class::migrate();
}
