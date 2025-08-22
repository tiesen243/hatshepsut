<?php

use Framework\Core\Database;
use Framework\Core\Router;
use Framework\Http\Request;
use Framework\Http\Response;

class Application
{
  public function __construct()
  {
    $config = require_once BASE_PATH . '/app/config.php';

    if ($config['connection']['enabled']) {
      Database::connect($config['connection']);
    }

    $this->loadRoutes();
  }

  public function run()
  {
    $request = Request::create();
    [$found, $handler, $vars] = Router::getRoute(
      $request->getMethod(),
      $request->getUri(),
    );

    $response = new Response('Not Found', 404);
    if (!$found) {
      return $response->send();
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
          $response = new Response('Method Not Found', 404);
        }
      } else {
        $response = new Response('Invalid Route Handler', 500);
      }

      if (!$response instanceof Response) {
        $response = new Response($response);
      }
    } catch (\Exception $e) {
      error_log('Error: ' . $e->getMessage());
      $response = new Response('Internal Server Error', 500);
    }

    $response->send();
  }

  private function loadRoutes()
  {
    $routesDir = BASE_PATH . '/routes';
    $files = scandir($routesDir);
    foreach ($files as $file) {
      if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require_once $routesDir . '/' . $file;
      }
    }
  }
}

return new Application();
