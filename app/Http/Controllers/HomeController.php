<?php

namespace App\Http\Controllers;

use Framework\Core\Database;
use Framework\Http\Response;

class HomeController
{
  public function index()
  {
    $pdo = Database::getConnection();
    $stmt = $pdo->query('SELECT 1');
    $result = $stmt->fetch();
    error_log('Database connection test result: '.print_r($result, true));

    return Response::json(['message' => 'Welcome to the Home Page!']);
  }
}
