<?php

namespace App\Controllers;

use Framework\Core\Controller;
use Framework\Core\Database;
use Framework\Http\Response;

class ApiController extends Controller
{
  public function health(): Response
  {
    $dbStatus = 'connected';

    try {
      Database::pdo()->query('SELECT 1');
    } catch (\Throwable) {
      $dbStatus = 'disconnected';
    }

    return $this->json([
      'status' => $dbStatus === 'connected' ? 'healthy' : 'unhealthy',
      'database' => $dbStatus,
      'timestamp' => date('c'),
      'message' =>
        $dbStatus === 'connected'
          ? 'Service is healthy and database connection is operational.'
          : 'Service is running but database connection failed.',
    ]);
  }
}
