<?php

namespace Framework\Core;

use Framework\Http\Request;
use Framework\Http\Response;

class Router
{
  private static array $routes = [];
  private static array $middlewares = [];

  private string $lastMethod = '';
  private string $lastPath = '';

  /**
   * Register a GET route.
   *
   * @param string $path     the URL path to match
   * @param mixed  $callback the handler function or method for the route
   *
   * @return self returns a new Router instance
   */
  public static function get(string $path, mixed $callback): self
  {
    return self::registerRoute($path, 'GET', $callback);
  }

  /**
   * Register a POST route.
   *
   * @param string $path     the URL path to match
   * @param mixed  $callback the handler function or method for the route
   *
   * @return self returns a new Router instance
   */
  public static function post(string $path, mixed $callback): self
  {
    return self::registerRoute($path, 'POST', $callback);
  }

  /**
   * Register a route with method, compiling regex and extracting param names.
   *
   * @param string $path     the URL path to match
   * @param string $method   The HTTP method (e.g., 'GET', 'POST').
   * @param mixed  $callback the handler function or method for the route
   *
   * @return self returns a new Router instance
   */
  private static function registerRoute(
    string $path,
    string $method,
    mixed $callback,
  ): self {
    [$regex, $paramNames] = self::compileRoutePattern($path);
    self::$routes[$method][$path] = [
      'regex' => $regex,
      'paramNames' => $paramNames,
      'callback' => $callback,
      'middlewares' => [],
    ];

    $instance = new self();
    $instance->lastMethod = $method;
    $instance->lastPath = $path;

    return $instance;
  }

  /**
   * Compile a route pattern to regex and extract parameter names.
   *
   * @return array [string $regex, array $paramNames]
   */
  private static function compileRoutePattern(string $path): array
  {
    $paramNames = [];
    $regex = preg_replace_callback(
      '#:([\w]+)#',
      function ($matches) use (&$paramNames) {
        $paramNames[] = $matches[1];

        return '([^/]+)';
      },
      $path,
    );
    $regex = str_replace('/*', '/.*', $regex);
    $regex = '#^'.$regex.'$#';

    return [$regex, $paramNames];
  }

  /**
   * Register a middleware to be executed before route handling.
   *
   * @param callable $middleware the middleware function to execute
   *
   * @return self returns a new Router instance
   */
  public static function registerMiddleware(
    Middleware $middleware,
  ): self {
    self::$middlewares[$middleware->getName()] = $middleware->canActivate(...);

    return new self();
  }

  /**
   * Attach a middleware to the last registered route.
   *
   * @param string|array $name the name or names of the middleware to attach
   *
   * @return self returns the current Router instance
   *
   * @throws \Exception if the middleware is not found or no route is registered
   */
  public function middleware(string|array $name): self
  {
    $names = is_array($name) ? $name : [$name];

    foreach ($names as $middlewareName) {
      if (!isset(self::$middlewares[$middlewareName])) {
        throw new \Exception("Middleware '{$middlewareName}' not found.");
      }

      if ($this->lastMethod && $this->lastPath) {
        self::$routes[$this->lastMethod][$this->lastPath][
          'middlewares'
        ][] = $name;
      }
    }

    return $this;
  }

  /**
   * Dispatch the request to the appropriate route handler.
   *
   * @param Request $request the incoming HTTP request
   */
  public static function dispatch(Request $request): void
  {
    $method = $request->server('REQUEST_METHOD');
    $path = rtrim($request->server('REQUEST_URI'), '/') ?: '/';
    $routes = self::sortRoutes(self::$routes[$method] ?? []);

    [$matchedRoute, $matchedParams] = self::matchRoute($routes, $path);

    if (!$matchedRoute) {
      Response::json(['status' => 404, 'message' => 'Not Found'], 404)->send();

      return;
    }

    try {
      foreach ($matchedRoute['middlewares'] as $middlewareName) {
        if (!self::$middlewares[$middlewareName]($request)) {
          Response::json(['message' => 'Forbidden'], 403)->send();

          return;
        }
      }

      $callback = $matchedRoute['callback'];
      $response = null;

      if (is_array($callback) && 2 === count($callback)) {
        [$class, $method] = $callback;
        $response = new $class()->{$method}($request, ...$matchedParams);
      } elseif (is_callable($callback)) {
        $response = $callback($request, ...$matchedParams);
      } else {
        throw new \Exception('Invalid route callback.');
      }

      if ($response instanceof Response) {
        $response->send();
      } else {
        new Response($response)->send();
      }
    } catch (\Throwable $e) {
      Response::json(
        ['message' => 'Internal Server Error', 'error' => $e->getMessage()],
        500,
      )->send();
    }
  }

  /**
   * Match the request path to a registered route.
   *
   * @return array [matchedRoute|null, matchedParams]
   */
  private static function matchRoute(array $routes, string $path): array
  {
    foreach ($routes as $routePattern => $routeInfo) {
      if (!isset($routeInfo['regex'])) {
        continue;
      }
      if (preg_match($routeInfo['regex'], $path, $matches)) {
        array_shift($matches);

        return [$routeInfo, $matches];
      }
    }

    return [null, []];
  }

  /**
   * Sort routes: static first, then dynamic, then catch-all.
   *
   * @param array $routes the routes to sort
   *
   * @return array the sorted routes
   */
  private static function sortRoutes(array $routes): array
  {
    $static = [];
    $dynamic = [];
    $catchAll = [];

    foreach ($routes as $path => $info) {
      if (str_contains($path, '/*')) {
        $catchAll[$path] = $info;
      } elseif (str_contains($path, ':')) {
        $dynamic[$path] = $info;
      } else {
        $static[$path] = $info;
      }
    }

    return array_merge($static, $dynamic, $catchAll);
  }
}
