<?php

namespace App\Controller;

use App\Models\Post;
use Framework\Core\Controller;
use Framework\Http\Response;

class PostController extends Controller
{
  public function __construct(private Post $model = new Post()) {}

  public function all(): Response
  {
    $posts = $this->model->findMany();
    return $this->json([
      'message' => 'Posts retrieved successfully',
      'data' => $posts,
    ]);
  }

  public function byId(string $id): Response
  {
    $post = $this->model->findOne($id);
    if (!$post) {
      return $this->json(['error' => 'Post not found'], 404);
    }
    return $this->json([
      'message' => 'Post found',
      'data' => $post->toArray(),
    ]);
  }
}
