<?php

namespace App\Controllers;

use App\Models\Post;
use Framework\Core\Controller;
use Framework\Http\Response;

class PostController extends Controller
{
  private Post $post;

  public function __construct()
  {
    $this->post = new Post();
  }

  public function getPosts(): Response
  {
    $posts = $this->post->findMany();
    return $this->json(array_map(fn ($post) => $post->toArray(), $posts));
  }

  public function getPost(int $id): Response
  {
    $post = $this->post->setId($id)->findOne();
    if ($post) {
      return $this->json($post->toArray());
    } else {
      return $this->json([
        'status' => 404,
        'error' => 'Post not found',
      ]);
    }
  }

  public function store(): Response
  {
    $data = $this->request->body();

    if (!empty($data['id'])) {
      $this->post->setId($data['id']);
    }
    $this->post->setTitle($data['title'] ?? '')->setContent($data['content'] ?? '')->store();

    return $this->json([
      'status' => 201,
      'message' => 'Post created successfully',
    ]);
  }

  public function delete(string $id): Response
  {
    $this->post->setId($id)->delete();
    return $this->json([
      'status' => 200,
      'message' => 'Post deleted successfully',
    ]);
  }
}
