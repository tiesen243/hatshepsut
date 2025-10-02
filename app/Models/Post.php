<?php

namespace App\Models;

use Framework\Core\Column;
use Framework\Core\Model;

class Post extends Model
{
  protected string $table = 'posts';

  #[Column('VARCHAR(24)', primary: true, nullable: false)]
  public string $id;

  #[Column('VARCHAR(255)', nullable: false)]
  public string $title;

  #[Column('TEXT', nullable: false)]
  public string $content;

  #[Column('TIMESTAMP', nullable: false, default: 'CURRENT_TIMESTAMP')]
  public string $created_at;

  #[Column('TIMESTAMP', nullable: false, default: 'CURRENT_TIMESTAMP', onUpdate: 'CURRENT_TIMESTAMP')]
  public string $updated_at;
}
