<?php

namespace App\Http\Middlewares;

use Framework\Http\Request;

class AuthMiddleware extends \Framework\Core\Middleware
{
  protected string $name = 'auth';

  public function canActivate(Request $req): bool
  {
    return (bool) random_int(0, 1);
  }
}
