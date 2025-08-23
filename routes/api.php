<?php

use App\Controller\ApiController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/api/health', [ApiController::class, 'health']);
