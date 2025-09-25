<?php

namespace Framework;

use Framework\Core\Database;
use Framework\Core\Router;
use Framework\Core\Template;
use Framework\Http\Request;

class Application
{
  private $request;

  public function __construct(private string $basePath)
  {
    $appConfig = require_once $this->basePath.'/config/app.php';

    // Initialize request
    $this->request = Request::create();

    // Setup database connection
    $this->setupDatabase();

    // Initialize template engine
    Template::create(
      $this->basePath.'/resources/views',
      $this->basePath.'/.cache/views',
      $appConfig,
    );
  }

  public function withMiddleware(array $middlewares): self
  {
    foreach ($middlewares as $middleware) {
      if (is_string($middleware) && class_exists($middleware))
        $middleware = new $middleware();
      Router::registerMiddleware($middleware);
    }

    return $this;
  }

  public function withRoutes(array $routes): self
  {
    foreach ($routes as $route) {
      require_once $this->basePath.'/routes/'.$route.'.php';
    }

    return $this;
  }

  public function run()
  {
    $this->setCors();
    Router::dispatch($this->request);
  }

  private function setCors()
  {
    $corsConfig = require_once $this->basePath.'/config/cors.php';
    header('Access-Control-Allow-Origin: '.implode(', ', $corsConfig['allowed_origins']));
    header('Access-Control-Allow-Methods: '.implode(', ', $corsConfig['allowed_methods']));
    header('Access-Control-Allow-Headers: '.implode(', ', $corsConfig['allowed_headers']));
    header('Access-Control-Allow-Credentials: '.($corsConfig['supports_credentials'] ? 'true' : 'false'));
    header('Access-Control-Max-Age: '.$corsConfig['max_age']);
  }

  private function setupDatabase()
  {
    $databaseConfig = require_once $this->basePath.'/config/database.php';
    Database::connect($databaseConfig);
  }
}
