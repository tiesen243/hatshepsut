<?php

namespace Framework\Http;

class Response
{
  public function __construct(
    private ?string $content = null,
    private int $statusCode = 200,
    private array $headers = [],
  ) {}

  public function send(): void
  {
    http_response_code($this->statusCode);
    foreach ($this->headers as $name => $value) {
      header("$name: $value");
    }

    if ($this->content !== null) {
      echo $this->content;
    }
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }
  public function getBody(): ?string
  {
    return $this->content;
  }
}
