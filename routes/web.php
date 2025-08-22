<?php

use App\Controller\HomeController;
use Framework\Core\Router;

$router = Router::getInstance();

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', function () {
  return 'This is the about page.';
});
