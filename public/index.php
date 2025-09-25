<?php

use App\Http\Middlewares\AuthMiddleware;
use Framework\Application;

require_once __DIR__.'/../vendor/autoload.php';

$application = new Application(dirname(__DIR__));
$application
  ->withMiddleware([AuthMiddleware::class])
  ->withRoutes(['web', 'api'])
  ->run();
