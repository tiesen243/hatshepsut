<?php

use App\Models\Post;
use Framework\Core\Database;
use Framework\Core\Model;

require_once __DIR__.'/vendor/autoload.php';

$databaseConfig = require_once __DIR__.'/config/database.php';

$db = Database::connect($databaseConfig);
Model::setDatabase($db);

Post::migrate();
