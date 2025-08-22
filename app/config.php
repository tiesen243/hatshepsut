<?php

use Framework\Core\Env;

return [
  'mode' => Env::get('MODE', 'production'),

  'vite_url' => Env::get('VITE_URL', 'http://[::0]:5173'),

  'connection' => [
    'enabled' => false,
    'host' => Env::get('DB_HOST', 'localhost'),
    'port' => Env::get('DB_PORT', '3306'),
    'database' => Env::get('DB_NAME', 'test'),
    'username' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ],
  ],
];
