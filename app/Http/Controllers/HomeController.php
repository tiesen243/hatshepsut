<?php

namespace App\Http\Controllers;

use Framework\Http\Response;

class HomeController
{
  public function index(): Response
  {
    return Response::view('routes.home.index');
  }
}
