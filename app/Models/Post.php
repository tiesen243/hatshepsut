<?php

namespace App\Models;

use Framework\Core\Model;

class Post extends Model
{
  protected string $table = 'posts';
  protected array $columns = [
    'id' => 'VARCHAR(36) PRIMARY KEY',
    'title' => 'VARCHAR(255) NOT NULL',
    'content' => 'TEXT NOT NULL',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
  ];
}
