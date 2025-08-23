<?php

use App\Controllers\ApiController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/api/health', [ApiController::class, 'health']);
