<?php

namespace Framework\Core;

abstract class Model
{
  protected static \PDO $db;

  protected string $table;
  protected array $columns = [];

  private string $query;
  private string $queryType;
  private array $bindings = [];

  public function __construct()
  {
    if (!isset(self::$db))
      self::$db = Database::getConnection();
  }

  public static function setDatabase(\PDO $db): void
  {
    self::$db = $db;
  }

  public static function migrate(): void
  {
    $instance = new static();
    if (empty($instance->columns)) return;

    $columnsSql = implode(",\n  ", array_map(
      fn ($type, $col) => "$col $type",
      $instance->columns,
      array_keys($instance->columns)
    ));
    $sql = "CREATE TABLE IF NOT EXISTS {$instance->table} ($columnsSql)";

    self::$db->exec($sql);
  }

  public static function findMany(string $column = '*'): self
  {
    $instance = new static();
    $instance->query = "SELECT $column FROM {$instance->table}";
    $instance->queryType = 'selectAll';

    return $instance;
  }

  public static function findOne($id, string $column = '*'): self
  {
    $instance = new static();
    $instance->query = "SELECT $column FROM {$instance->table} WHERE id = :id LIMIT 1";
    $instance->queryType = 'selectOne';
    $instance->bindings = [':id' => $id];

    return $instance;
  }

  public static function create(array $data): array
  {
    $instance = new static();

    $data['id'] = uniqid('c', true);
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_map(fn ($key) => ":$key", array_keys($data)));

    $instance->query = "INSERT INTO {$instance->table} ($columns) VALUES ($placeholders)";
    $instance->bindings = [];
    foreach ($data as $key => $value) $instance->bindings[":$key"] = $value;

    $stmt = self::$db->prepare($instance->query);
    $stmt->execute($instance->bindings);

    return $data;
  }

  public static function update($id, array $data): bool
  {
    $instance = new static();

    $setClause = implode(', ', array_map(fn ($key) => "$key = :$key", array_keys($data)));

    $instance->query = "UPDATE {$instance->table} SET $setClause WHERE id = :id";
    $instance->bindings = [];
    $instance->bindings[':id'] = $id;
    foreach ($data as $key => $value)
      $instance->bindings[":$key"] = $value;

    $stmt = self::$db->prepare($instance->query);

    return $stmt->execute($instance->bindings);
  }

  public static function delete($id): bool
  {
    $instance = new static();
    $instance->query = "DELETE FROM {$instance->table} WHERE id = :id";
    $instance->bindings[':id'] = $id;

    $stmt = self::$db->prepare($instance->query);

    return $stmt->execute($instance->bindings);
  }

  public function where(string $column, string $operator, $value): self
  {
    $clause = (false === stripos($this->query, 'WHERE')) ? ' WHERE ' : ' AND ';
    $param = ':'.$column.count($this->bindings);
    $this->query .= "{$clause}{$column} {$operator} {$param}";
    $this->bindings[$param] = $value;

    return $this;
  }

  public function orWhere(string $column, string $operator, $value): self
  {
    $clause = (false === stripos($this->query, 'WHERE')) ? ' WHERE ' : ' OR ';
    $param = ':'.$column.count($this->bindings);
    $this->query .= "{$clause}{$column} {$operator} {$param}";
    $this->bindings[$param] = $value;

    return $this;
  }

  public function orderBy(string $column, string $direction = 'ASC'): self
  {
    $this->query .= " ORDER BY $column $direction";

    return $this;
  }

  public function limit(int $limit): self
  {
    $this->query .= " LIMIT $limit";

    return $this;
  }

  public function offset(int $offset): self
  {
    $this->query .= " OFFSET $offset";

    return $this;
  }

  public function join(string $table, string $firstColumn, string $secondColumn, string $type = 'INNER'): self
  {
    $this->query .= " $type JOIN $table ON $firstColumn = $secondColumn";

    return $this;
  }

  public function execute(): ?array
  {
    $stmt = self::$db->prepare($this->query);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
    $stmt->execute($this->bindings);

    if ('selectOne' === $this->queryType)
      return $stmt->fetch() ?: null;

    return $stmt->fetchAll();
  }
}
