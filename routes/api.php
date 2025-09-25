<?php

use App\Http\Controllers\PostController;
use Framework\Core\Router;
use Framework\Http\Response;

Router::get('/api/health', function () {
  return Response::json(['status' => 'ok', 'timestamp' => time()]);
});

Router::post('/api/posts', [PostController::class, 'store']);
