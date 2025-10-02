<?php

namespace Framework\Core;

use Framework\Http\Request;

abstract class Controller
{
  protected Request $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }
}
