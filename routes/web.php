<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Framework\Core\Router;

Router::get('/', [HomeController::class, 'index']);

Router::get('/posts', [PostController::class, 'index']);
Router::get('/posts/:id', [PostController::class, 'show']);
