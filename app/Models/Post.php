<?php

namespace App\Models;

use DateTime;
use Framework\Core\Database;
use Framework\Http\HttpError;

class Post
{
  public function __construct(
    private string $id = '',
    private string $title = '',
    private string $content = '',
    private DateTime $createdAt = new DateTime(),
    private DateTime $updatedAt = new DateTime(),
  ) {
  }

  public function findMany(): array
  {
    $pdo = Database::pdo();
    $stmt = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
    $posts = [];
    while ($row = $stmt->fetch()) {
      $posts[] = new Post(
        $row['id'],
        $row['title'],
        $row['content'],
        new DateTime($row['created_at']),
        new DateTime($row['updated_at'])
      );
    }
    return $posts;
  }

  public function findOne(): Post
  {
    $pdo = Database::pdo();
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->execute(['id' => $this->id]);
    $row = $stmt->fetch();

    if (!$row) {
      throw HttpError::notFound('Post not found');
    }


    return new Post(
      $row['id'],
      $row['title'],
      $row['content'],
      new DateTime($row['created_at']),
      new DateTime($row['updated_at'])
    );
  }

  public function store(): Post
  {
    $pdo = Database::pdo();
    $this->createdAt = new DateTime();
    $this->updatedAt = new DateTime();

    if ($this->id) {
      $this->findOne();
      $stmt = $pdo->prepare('UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id');
      $stmt->execute([
        'id' => $this->id,
        'title' => $this->title,
        'content' => $this->content,
        'updated_at' => $this->updatedAt->format('c'),
      ]);
    } else {
      $stmt = $pdo->prepare('INSERT INTO posts (id, title, content, created_at, updated_at) VALUES (UUID(), :title, :content, :created_at, :updated_at)');
      $stmt->execute([
        'title' => $this->title,
        'content' => $this->content,
        'created_at' => $this->createdAt->format('c'),
        'updated_at' => $this->updatedAt->format('c'),
      ]);
    }

    return $this;
  }

  public function delete(): void
  {
    if (!$this->id) {
      throw new \Exception('ID is required to delete a post.');
    }

    $pdo = Database::pdo();

    $this->findOne();
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->execute(['id' => $this->id]);
  }

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

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }
  public function setCreatedAt(DateTime $createdAt): self
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }
  public function setUpdatedAt(DateTime $updatedAt): self
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'content' => $this->content,
      'createdAt' => $this->createdAt->format('c'),
      'updatedAt' => $this->updatedAt->format('c'),
    ];
  }
}
