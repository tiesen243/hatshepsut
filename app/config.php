<?php

use Framework\Core\Env;

return [
  /*
   * ------------------------------------------------------------
   * Application Mode
   * ------------------------------------------------------------
   *
   * This setting determines the mode your application is currently
   * running in. It can be set to 'development' or 'production'.
   */
  'mode' => Env::get('MODE', 'development'),

  /*
   * ------------------------------------------------------------
   * Vite URL
   * ------------------------------------------------------------
   *
   * This setting defines the URL for the Vite development server.
   * It is used to serve assets during development. Make sure to
   * set this to the correct URL where your Vite server is running.
   */
  'vite_url' => Env::get('VITE_URL', 'http://[::0]:5173'),

  /*
   * ------------------------------------------------------------
   * Database Connection
   * ------------------------------------------------------------
   *
   * This section contains the configuration for the database
   * connection. You can enable or disable the connection and
   * set the necessary parameters such as host, port, database,
   * username, and password.
   */
  'database' => [
    'enabled' => false,
    'host' => Env::get('DB_HOST', 'localhost'),
    'port' => Env::get('DB_PORT', '3306'),
    'name' => Env::get('DB_NAME', 'test'),
    'username' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ],
  ],
];
