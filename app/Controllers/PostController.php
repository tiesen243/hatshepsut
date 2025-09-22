<?php

namespace App\Controllers;

use App\Models\Post;
use Framework\Core\Controller;
use Framework\Http\Response;

class PostController extends Controller
{
  public function getPosts(): Response
  {
    $posts = Post::select(['id', 'title', 'content' ])->execute();
    return $this->json($posts);
  }

  public function getPost(int $id): Response
  {
    $post = Post::select([ 'id', 'title', 'content', 'created_at' ])
      ->where(['id', '=', $id])
      ->limit(1)->execute();

    if (!$post) {
      return $this->json([ 'error' => 'Post not found' ], 404);
    }

    return $this->json($post);
  }
}
