<?php

namespace Framework\Http;

class Request
{
  private static $instance = null;

  private function __construct(
    private array $server,
    private array $cookies,
    private array $get,
    private array $post,
    private array $files,
  ) {}

  public static function create(): static
  {
    if (self::$instance === null) {
      self::$instance = new static($_SERVER, $_COOKIE, $_GET, $_POST, $_FILES);
    }

    return self::$instance;
  }

  public function getUri(): string
  {
    $uri = $_SERVER['REQUEST_URI'] ?: '/';
    $parsedUri = parse_url($uri) ?: '';
    return $parsedUri['path'] ?? '/';
  }

  public function getMethod(): string
  {
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
  }
}
