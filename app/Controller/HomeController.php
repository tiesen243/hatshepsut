<?php

namespace App\Controller;

use Framework\Core\Controller;

class HomeController extends Controller
{
  public function index()
  {
    return $this->view('index', [
      'isGay' => false,
    ]);
  }
}
