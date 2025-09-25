<?php

namespace App\Http\Controllers;

use Framework\Http\Response;

class HomeController
{
  public function index(): Response
  {
    return Response::json([
      'message' => 'Welcome to the HomeController index method!',
      'routes' => [
        '/api/health' => 'Health Check',
        '/api/posts/store' => 'PostController@store',
        '/api/posts/:id/delete' => 'PostController@delete',
        '/api/protected' => 'Protected API Route (auth middleware)',

        '/' => 'HomeController@index',
        '/posts' => 'PostController@index',
        '/posts/create' => 'PostController@create',
        '/posts/:id' => 'PostController@show',

        '/*' => '404 Not Found',
      ],
    ]);
  }
}
