<?php

namespace Framework\Http;

class Request
{
  private static ?Request $instance = null;

  public function __construct(
    private array $get,
    private array $post,
    private array $server,
    private array $cookies,
    private array $files,
  ) {
  }

  public static function create(): Request
  {
    if (null === self::$instance) {
      self::$instance = new static($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);
    }

    return self::$instance;
  }

  public static function getInstance(): ?Request
  {
    return self::$instance;
  }

  public function server(string $key, $default = null)
  {
    return $this->server[$key] ?? $default;
  }

  public function cookie(string $key, $default = null)
  {
    return $this->cookies[$key] ?? $default;
  }

  public function query(): array
  {
    return $this->get;
  }

  public function input(): array
  {
    return array_merge($this->post, $this->files);
  }
}
