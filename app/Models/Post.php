<?php

namespace App\Models;

use Framework\Core\Model;

class Post extends Model
{
  protected string $name = 'posts';

  public function createTable(): void
  {
    $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS {$this->name} (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      content TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt->execute();
  }
}
