<?php

namespace Framework\Http;

use Framework\Core\Template;

class Response
{
  public function __construct(
    private ?string $content = null,
    private int $statusCode = 200,
    private array $headers = [],
  ) {}

  public static function json(
    $data,
    int $statusCode = 200,
    array $headers = [],
  ): self {
    $headers['Content-Type'] = 'application/json';

    return new self(json_encode($data), $statusCode, $headers);
  }

  public static function redirect(string $url, int $statusCode = 302): self
  {
    $headers = ['Location' => $url];

    return new self(null, $statusCode, $headers);
  }

  public static function view(
    string $view,
    array $data = [],
    int $statusCode = 200,
    array $headers = [],
  ): self {
    $headers['Content-Type'] = 'text/html';
    $content = Template::getInstance()->render($view, $data);

    return new self($content, $statusCode, $headers);
  }

  public function send(): void
  {
    http_response_code($this->statusCode);
    foreach ($this->headers as $name => $value) {
      header("{$name}: {$value}");
    }

    if (null !== $this->content) {
      echo $this->content;
    }
  }
}
