<?php

use App\Http\Controllers\PostController;
use Framework\Core\Router;
use Framework\Http\Response;

Router::get('/api/health', function () {
  return Response::json(['status' => 'ok', 'timestamp' => time()]);
});

Router::post('/api/posts/store', [PostController::class, 'store']);
Router::post('/api/posts/:id/delete', [PostController::class, 'delete']);

Router::get('/api/protected', function () {
  return Response::json(['message' => 'You have accessed a protected API route.']);
})->middleware('auth');
