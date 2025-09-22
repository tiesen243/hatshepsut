<?php

use App\Controllers\HomeController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/*', [HomeController::class, 'index']);
