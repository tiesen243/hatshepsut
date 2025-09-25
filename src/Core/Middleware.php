<?php

namespace Framework\Core;

use Framework\Http\Request;

abstract class Middleware
{
  protected string $name;

  abstract protected function canActivate(Request $request): bool;

  public function getName(): string
  {
    return $this->name;
  }
}
