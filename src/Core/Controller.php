<?php

namespace Framework\Core;

use Framework\Http\Request;
use Framework\Http\Response;

abstract class Controller
{
  protected ?Request $request = null;

  public function setRequest(Request $request): void
  {
    $this->request = $request;
  }

  protected function json(array $data): Response
  {
    return new Response(json_encode($data), 200, [
      'Content-Type' => 'application/json',
    ]);
  }

  protected function redirect(string $url): Response
  {
    return new Response(null, 302, [
      'Location' => $url,
    ]);
  }
}
