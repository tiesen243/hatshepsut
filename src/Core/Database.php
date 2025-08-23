<?php

namespace Framework\Core;

use PDO;

class Database
{
  private PDO $pdo;
  private static ?Database $instance = null;

  private function __construct(array $config)
  {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    $username = $config['username'] ?? '';
    $password = $config['password'] ?? '';
    $options = $config['options'] ?? [];

    try {
      $this->pdo = new \PDO($dsn, $username, $password, $options);
      $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      throw new \RuntimeException(
        'Database connection failed: ' . $e->getMessage(),
        0,
      );
    }
  }

  public static function connect(array $config): Database
  {
    if (self::$instance === null) {
      self::$instance = new Database($config);
    }
    return self::$instance;
  }

  public static function getPdo(): ?PDO
  {
    return self::$instance->pdo ?? null;
  }
}
