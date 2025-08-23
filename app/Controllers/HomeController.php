<?php

namespace App\Controllers;

use Framework\Core\Controller;

class HomeController extends Controller
{
  public function index()
  {
    return $this->view('routes.index');
  }
}
