<?php

use App\Http\Controllers\PostController;
use Framework\Core\Router;

Router::get('/api/health', function () {
  return Framework\Http\Response::json(['status' => 'ok', 'timestamp' => time()]);
});

Router::get('/api/protected', function () {
  return Framework\Http\Response::json(['message' => 'You have accessed a protected API route.']);
})->middleware('auth');

Router::get('/api/posts', [PostController::class, 'getPosts']);
Router::get('/api/posts/:id', [PostController::class, 'getPost']);
Router::post('/api/posts/create', [PostController::class, 'store'])->middleware('auth');
Router::post('/api/posts/:id/edit', [PostController::class, 'store'])->middleware('auth');
Router::post('/api/posts/:id/delete', [PostController::class, 'delete'])->middleware('auth');
