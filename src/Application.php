<?php

namespace Framework;

use Framework\Core\Database;
use Framework\Core\Router;
use Framework\Core\Template;
use Framework\Http\HttpError;
use Framework\Http\Request;
use Framework\Http\Response;

class Application
{
  private array $config;

  public function __construct(private string $basePath)
  {
    $this->config = require_once $basePath . '/app/config.php';

    if ($this->config['database']['enabled']) {
      Database::connect($this->config['database']);
    }

    Template::create(
      $basePath . '/resources/views',
      $basePath . '/.cache/views',
      $this->config['vite_url'],
      $this->config['mode'],
    );

    $this->loadRoutes();
  }

  public function run()
  {
    $request = Request::create();
    [$found, $handler, $vars] = Router::getRoute(
      $request->getMethod(),
      $request->getUri(),
    );

    if (!$found) {
      return HttpError::notFound()->send();
    }

    try {
      if (is_callable($handler)) {
        $response = call_user_func($handler, $vars);
      } elseif (is_array($handler) && count($handler) === 2) {
        [$controller, $method] = $handler;
        $controller = new $controller();
        $controller->setRequest($request);

        if (method_exists($controller, $method)) {
          $response = call_user_func_array([$controller, $method], $vars);
        } else {
          $response = HttpError::notFound('Method Not Found');
        }
      } else {
        $response = HttpError::forbidden('Invalid Handler');
      }

      if (!$response instanceof Response && !$response instanceof HttpError) {
        $response = new Response($response);
      }
    } catch (\Throwable $e) {
      error_log('Error: ' . $e->getMessage());
      if ($e instanceof HttpError) {
        $response = $e;
      } else {
        $response = HttpError::serverError(
          'Internal Server Error',
          $e->getMessage(),
        );
      }
    }

    $response->send();
  }

  private function loadRoutes()
  {
    $routesDir = $this->basePath . '/routes';
    $files = scandir($routesDir);
    foreach ($files as $file) {
      if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require_once $routesDir . '/' . $file;
      }
    }
  }
}
