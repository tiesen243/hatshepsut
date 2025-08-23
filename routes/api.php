<?php

use App\Controller\PostController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/api/posts', [PostController::class, 'all']);
$router->get('/api/posts/:id', [PostController::class, 'byId']);
