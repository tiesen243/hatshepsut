<?php

use App\Controllers\PostController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/api/posts', [PostController::class, 'getPosts']);
$router->get('/api/posts/:id', [PostController::class, 'getPost']);
