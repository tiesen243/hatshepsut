<?php

namespace App\Models;

use DateTime;
use Framework\Core\Database;

class Post
{
  public function __construct(
    private string $id = '',
    private string $title = '',
    private string $content = '',
    private DateTime $createdAt = new DateTime(),
  ) {}

  public function findMany(): array
  {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare(/* SQL */ 'SELECT * FROM post');
    $stmt->execute();
    $posts = $stmt->fetchAll();
    return array_map(
      fn($post) => new Post(
        $post['id'],
        $post['title'],
        $post['content'],
        new DateTime($post['created_at']),
      ),
      $posts,
    );
  }

  public function findOne(string $id): ?Post
  {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare(/* SQL */ 'SELECT * FROM post WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch();
    return $post
      ? new Post(
        $post['id'],
        $post['title'],
        $post['content'],
        new DateTime($post['created_at']),
      )
      : null;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'content' => $this->content,
      'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
    ];
  }

  /**
   * Getters and Setters
   */
  public function getId(): string
  {
    return $this->id;
  }
  public function getTitle(): string
  {
    return $this->title;
  }
  public function getContent(): string
  {
    return $this->content;
  }
  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  /**
   * Seed the database with initial data
   */
  public function seed(): void
  {
    $pdo = Database::getPdo();

    $stmt = $pdo->prepare(/* SQL */ 'CREATE TABLE IF NOT EXISTS post (
      id CHAR(36) PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      content TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )');
    $stmt->execute();

    $stmt = $pdo->prepare(/* SQL */ 'SELECT COUNT(*) as count FROM post');
    $stmt->execute();
    $count = $stmt->fetch()['count'] ?? 0;
    if ($count == 0) {
      for ($i = 1; $i <= 10; $i++) {
        $stmt = $pdo->prepare(
          'INSERT INTO post (id, title, content) VALUES (UUID(), :title, :content)',
        );
        $stmt->execute([
          'title' => "Post $i",
          'content' => "This is the content of post $i.",
        ]);
      }
    }
  }
}
