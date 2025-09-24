<?php

namespace Framework\Core;

class Database
{
  private static ?Database $instance = null;
  private \PDO $pdo;

  public function __construct(private array $config)
  {
    $connection = $config['connections'][$config['default']];
    $driver = $connection['driver'];

    switch ($driver) {
      case 'mysql':
        $dsn = "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['database']};charset={$connection['charset']}";
        $username = $connection['username'];
        $password = $connection['password'];
        $options = $connection['options'] ?? [];

        break;

      case 'pgsql':
        $dsn = "pgsql:host={$connection['host']};port={$connection['port']};dbname={$connection['database']};options='--client_encoding={$connection['charset']}'";
        $username = $connection['username'];
        $password = $connection['password'];
        $options = $connection['options'] ?? [];

        break;

      case 'sqlite':
        $dsn = "sqlite:{$connection['database']}";
        $username = null;
        $password = null;
        $options = $connection['options'] ?? [];

        break;

      default:
        throw new \InvalidArgumentException("Unsupported driver: {$driver}");
    }

    try {
      $this->pdo = new \PDO($dsn, $username, $password, $options);
    } catch (\PDOException $e) {
      throw new \RuntimeException('Database connection failed: '.$e->getMessage());
    }
  }

  public static function connect(array $config): self
  {
    if (null === self::$instance) {
      self::$instance = new self($config);
    }

    return self::$instance;
  }

  public static function getConnection(): \PDO
  {
    if (null === self::$instance) {
      throw new \Exception('Database not connected. Call connect() first.');
    }

    return self::$instance->pdo;
  }
}
