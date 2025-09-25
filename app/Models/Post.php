<?php

namespace App\Models;

use Framework\Core\Database;

class Post
{
  public function __construct(
    private string $id = '',
    private string $title = '',
    private string $content = '',
    private \DateTime $createdAt = new \DateTime(),
    private \DateTime $updatedAt = new \DateTime(),
  ) {
  }

  public static function findMany()
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC');
    $stmt->execute();
    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return array_map(
      fn ($result) => new Post(
        $result['id'],
        $result['title'],
        $result['content'],
        new \DateTime($result['created_at']),
        new \DateTime($result['updated_at']),
      ),
      $results,
    );
  }

  public static function findOne(string $id): ?Post
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $row
      ? new Post(
        $row['id'],
        $row['title'],
        $row['content'],
        new \DateTime($row['created_at']),
        new \DateTime($row['updated_at']),
      )
      : null;
  }

  public function save()
  {
    $pdo = Database::getConnection();

    if ($this->id) {
      $stmt = $pdo->prepare(
        'UPDATE posts SET title = :title, content = :content, updated_at = NOW() WHERE id = :id',
      );
      $stmt->execute([
        'id' => $this->id,
        'title' => $this->title,
        'content' => $this->content,
      ]);
    } else {
      $stmt = $pdo->prepare(
        'INSERT INTO posts (id, title, content, created_at, updated_at) VALUES (UUID(), :title, :content, NOW(), NOW())',
      );
      $stmt->execute([
        'title' => $this->title,
        'content' => $this->content,
      ]);
    }

    if (!$this->id) {
      $stmt = $pdo->query(
        'SELECT id FROM posts ORDER BY created_at DESC LIMIT 1',
      );
      $this->id = $stmt->fetchColumn();
    }

    return $this;
  }

  // Getters and Setters
  public function getId(): string
  {
    return $this->id;
  }

  public function setId(string $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $this->content = $content;

    return $this;
  }

  public function getCreatedAt(): \DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTime $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): \DateTime
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(\DateTime $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  // Convert to array
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'content' => $this->content,
      'createdAt' => $this->createdAt->format(\DateTime::ATOM),
      'updatedAt' => $this->updatedAt->format(\DateTime::ATOM),
    ];
  }
}
