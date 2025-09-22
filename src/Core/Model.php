<?php

namespace Framework\Core;

use PDO;

enum Action: string
{
  case SELECT = 'select';
  case INSERT = 'insert';
  case UPDATE = 'update';
  case DELETE = 'delete';
}


abstract class Model
{
  protected string $name;
  protected Action $action;

  protected array $fields = [];
  protected string $conditions = '';
  protected ?int $limit = null;
  protected ?int $offset = null;
  protected array $order = [];


  protected PDO $db;

  public function __construct()
  {
    $this->db = Database::pdo();
  }


  public static function select(array $fields): static
  {
    $instance = new static();
    $instance->action = Action::SELECT;
    $instance->fields = $fields;
    return $instance;
  }

  public static function insert(array $data): static
  {
    $instance = new static();
    $instance->action = Action::INSERT;
    $instance->fields = $data;
    return $instance;
  }

  public static function update(array $data): static
  {
    $instance = new static();
    $instance->action = Action::UPDATE;
    $instance->fields = $data;
    return $instance;
  }

  public static function delete(): static
  {
    $instance = new static();
    $instance->action = Action::DELETE;
    return $instance;
  }

  public function where(array $conditions): self
  {
    $wheres = [];
    if (isset($conditions[0]) && !is_array($conditions[0])) {
      [$field, $op, $value] = $conditions;
      $wheres[] = "$field $op " . $this->db->quote($value);
    } else {
      foreach ($conditions as $cond) {
        [$field, $op, $value] = $cond;
        $wheres[] = "$field $op " . $this->db->quote($value);
      }
    }

    $this->conditions = implode(' AND ', $wheres);
    return $this;
  }

  public function limit(int $limit): self
  {
    $this->limit = $limit;
    return $this;
  }

  public function offset(int $offset): self
  {
    $this->offset = $offset;
    return $this;
  }

  public function orderBy(array $order): self
  {
    $this->order = $order;
    return $this;
  }

  public function execute()
  {
    switch ($this->action) {
      case Action::SELECT:
        $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->name;
        if (!empty($this->conditions)) {
          $sql .= ' WHERE ' . $this->conditions;
        }
        if (!empty($this->order)) {
          $orders = [];
          foreach ($this->order as $field => $dir) {
            $orders[] = "$field " . strtoupper($dir);
          }
          $sql .= ' ORDER BY ' . implode(', ', $orders);
        }
        if ($this->limit !== null) {
          $sql .= ' LIMIT ' . $this->limit;
        }
        if ($this->offset !== null) {
          $sql .= ' OFFSET ' . $this->offset;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results ?: [];
      case Action::INSERT:
        $fields = array_keys($this->fields);
        $placeholders = array_map(fn ($f) => ':' . $f, $fields);
        $sql = 'INSERT INTO ' . $this->name . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->fields);
        return $this->db->lastInsertId();
      case Action::UPDATE:
        $set = [];
        foreach ($this->fields as $field => $value) {
          $set[] = "$field = :$field";
        }
        $sql = 'UPDATE ' . $this->name . ' SET ' . implode(', ', $set);
        if (!empty($this->conditions)) {
          $sql .= ' WHERE ' . $this->conditions;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->fields);
        return $stmt->rowCount();
      case Action::DELETE:
        $sql = 'DELETE FROM ' . $this->name;
        if (!empty($this->conditions)) {
          $sql .= ' WHERE ' . $this->conditions;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
      default:
        throw new \Exception('Invalid action');
    }
  }

  abstract public function createTable(): void;
}
