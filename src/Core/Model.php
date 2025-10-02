<?php

namespace Framework\Core;

abstract class Model
{
  protected static \PDO $db;

  protected string $table;

  private string $query;
  private string $queryType;
  private array $bindings = [];
  private bool $incrementing = false;

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
    $reflection = new \ReflectionClass($instance);

    $columns = [];
    $foreignKeys = [];

    foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
      foreach ($prop->getAttributes(Column::class) as $attribute) {
        /** @var Column $meta */
        $meta = $attribute->newInstance();
        $columnDef = "{$prop->getName()} {$meta->type}";
        if ($meta->primary) $columnDef .= ' PRIMARY KEY';
        if (!$meta->nullable) $columnDef .= ' NOT NULL';
        if ($meta->unique) $columnDef .= ' UNIQUE';
        if (null !== $meta->default) $columnDef .= " DEFAULT {$meta->default}";
        if (null !== $meta->onUpdate) $columnDef .= " ON UPDATE {$meta->onUpdate}";
        $columns[] = $columnDef;

        if ($meta->primary && 'INT' === $meta->type) $instance->incrementing = true;
      }

      foreach ($prop->getAttributes(Relation::class) as $attribute) {
        /** @var Relation $meta */
        $meta = $attribute->newInstance();
        $fkDef = "FOREIGN KEY ({$prop->getName()}) REFERENCES {$meta->references}";

        if (null !== $meta->onDelete) $fkDef .= " ON DELETE {$meta->onDelete}";
        if (null !== $meta->onUpdate) $fkDef .= " ON UPDATE {$meta->onUpdate}";

        $foreignKeys[] = $fkDef;
      }
    }

    if (empty($columns)) return;

    $allDefs = array_merge($columns, $foreignKeys);
    $columnsSql = implode(",\n  ", $allDefs);
    $sql = "CREATE TABLE IF NOT EXISTS {$instance->table} (\n  $columnsSql\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
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
    if (empty($data)) return [];

    $instance = new static();
    if (!$instance->incrementing) $data['id'] = uniqid('c', true);
    elseif (!isset($data['id'])) $data['id'] = null;

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

  public function execute(): mixed
  {
    $stmt = self::$db->prepare($this->query);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
    $stmt->execute($this->bindings);

    if ('selectOne' === $this->queryType)
      return $stmt->fetch() ?: null;

    return $stmt->fetchAll();
  }
}
