<?php

use Framework\Core\Router;

Router::get('/*', function () {
  return Framework\Http\Response::view('app');
});
