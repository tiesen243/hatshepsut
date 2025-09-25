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

    // Load routes
    require_once $this->basePath.'/routes/api.php';

    require_once $this->basePath.'/routes/web.php';
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
