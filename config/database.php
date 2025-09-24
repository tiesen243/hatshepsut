<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Default Database Connection Name
  |--------------------------------------------------------------------------
  |
  | Here you may specify which of the database connections below you wish
  | to use as your default connection for all database work. Of course
  | you may use many connections at once using the Database library.
  |
  */
  'default' => getenv('DB_CONNECTION') ?: 'mysql',

  /*
  |--------------------------------------------------------------------------
  | Database Connections
  |--------------------------------------------------------------------------
  |
  | Below are all of the database connections defined for your application.
  | An example configuration is provided for each database system which
  | is supported by Laravel. You're free to add / remove connections.
  |
  */
  'connections' => [
    'mysql' => [
      'driver' => 'mysql',
      'host' => getenv('DB_HOST') ?: '127.0.0.1',
      'port' => getenv('DB_PORT') ?: '3306',
      'database' => getenv('DB_DATABASE') ?: 'db',
      'username' => getenv('DB_USERNAME') ?: 'root',
      'password' => getenv('DB_PASSWORD') ?: '',
      'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
      'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ],
    ],
    'sqlite' => [
      'driver' => 'sqlite',
      'database' => getenv('DB_DATABASE') ?: __DIR__.'database.sqlite',
    ],
    'postgres' => [
      'driver' => 'pgsql',
      'host' => getenv('DB_HOST') ?: '127.0.0.1',
      'port' => getenv('DB_PORT') ?: '5432',
      'database' => getenv('DB_DATABASE') ?: 'db',
      'username' => getenv('DB_USERNAME') ?: 'postgres',
      'password' => getenv('DB_PASSWORD') ?: '',
      'charset' => getenv('DB_CHARSET') ?: 'utf8',
      'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ],
    ],
  ],
];
