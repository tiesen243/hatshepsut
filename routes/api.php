<?php

use App\Controllers\PostController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/api/posts', [PostController::class, 'getPosts']);
$router->post('/api/posts', [PostController::class, 'store']);
$router->get('/api/posts/:id', [PostController::class, 'getPost']);
$router->post('/api/posts/:id', [PostController::class, 'delete']);
