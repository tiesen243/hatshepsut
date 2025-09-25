<?php

namespace App\Http\Controllers;

use Framework\Http\Response;

class HomeController
{
  public function index(): Response
  {
    return Response::json(['message' => 'Welcome to the Home Page!']);
  }
}
