<?php

namespace Framework\Core;

class Router
{
  private static Router $instance;
  private static array $routes = [];

  public function get(string $path, $handler): static
  {
    self::$routes['GET'][$path] = $handler;
    return $this;
  }

  public function post(string $path, $handler): static
  {
    self::$routes['POST'][$path] = $handler;
    return $this;
  }

  public static function getInstance(): static
  {
    if (!isset(self::$instance)) {
      self::$instance = new static();
    }
    return self::$instance;
  }

  public static function getRoute(string $method, string $path)
  {
    if (isset(self::$routes[$method][$path])) {
      return [1, self::$routes[$method][$path], []];
    }

    foreach (self::$routes[$method] ?? [] as $route => $handler) {
      if (strpos($route, '*') !== false || strpos($route, ':') === false) {
        continue;
      }

      $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route);
      $pattern = "#^$pattern$#";
      if (preg_match($pattern, $path, $matches)) {
        array_shift($matches);
        preg_match_all('#:([\w]+)#', $route, $paramNames);
        $paramNames = $paramNames[1];
        $vars = array_combine($paramNames, $matches);
        return [1, $handler, $vars ?: []];
      }
    }

    foreach (self::$routes[$method] ?? [] as $route => $handler) {
      if (substr($route, -2) === '/*') {
        $base = rtrim(substr($route, 0, -2), '/');
        if ($base === '' || strpos($path, $base) === 0) {
          return [1, $handler, []];
        }
      }
    }

    return [0, null, []];
  }
}
