<?php

namespace Framework\Http;

class HttpError extends \Exception
{
  public function __construct(
    int $code,
    string $message,
    protected ?string $details = null,
    ?\Throwable $previous = null,
  ) {
    parent::__construct($message, $code, $previous);
  }

  public static function badRequest(
    $message = 'Bad Request',
    $details = null,
  ): self {
    return new self(400, $message, $details);
  }

  public static function unauthorized(
    $message = 'Unauthorized',
    $details = null,
  ): self {
    return new self(401, $message, $details);
  }

  public static function forbidden(
    $message = 'Forbidden',
    $details = null,
  ): self {
    return new self(403, $message, $details);
  }

  public static function notFound($message = 'Not Found', $details = null): self
  {
    return new self(404, $message, $details);
  }

  public static function conflict($message = 'Conflict', $details = null): self
  {
    return new self(409, $message, $details);
  }

  public static function unprocessableEntity(
    $message = 'Unprocessable Entity',
    $details = null,
  ): self {
    return new self(422, $message, $details);
  }

  public static function serverError(
    $message = 'Internal Server Error',
    $details = null,
  ): self {
    return new self(500, $message, $details);
  }

  public function getStatusCode(): int
  {
    return $this->code;
  }

  public function send(): void
  {
    http_response_code($this->code);
    header('Content-Type: application/json; charset=UTF-8');

    $response = ['error' => $this->message];
    if ($this->details !== null) {
      $response['details'] = $this->details;
    }

    echo json_encode($response);
  }
}
