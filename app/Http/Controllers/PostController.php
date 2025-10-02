<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Framework\Core\Controller;
use Framework\Core\Validator;
use Framework\Http\Response;

class PostController extends Controller
{
  public function getPosts(): Response
  {
    $limit = $this->request->query('limit', 10);
    $page = $this->request->query('page', 1);

    $posts = Post::findMany()->limit($limit)->offset(($page - 1) * $limit)->execute();
    $total = Post::findMany('COUNT(*) as count')->execute()[0]->count;

    return Response::json([
      'message' => 'Posts retrieved successfully',
      'data' => [
        'posts' => $posts,
        'total' => $total,
        'page' => (int) $page,
        'total_pages' => ceil($total / $limit),
      ],
    ]);
  }

  public function getPost(string $id): Response
  {
    $post = Post::findOne($id)->execute();
    if (!$post) return Response::json(['message' => 'Post not found'], 404);

    return Response::json([
      'message' => 'Post retrieved successfully',
      'data' => $post,
    ]);
  }

  public function store(?string $id = null): Response
  {
    $parsed = new Validator([
      'title' => 'string>=5<=100',
      'content' => 'string>=10',
    ])->parse($this->request->json());
    if (!$parsed->isValid())
      return Response::json([
        'message' => 'Validation failed',
        'error' => $parsed->errors(),
      ], 400);

    $data = $parsed->data();

    if ($id) $post = Post::update($id, $data);
    else $post = Post::create($data);

    return Response::json([
      'message' => 'Post created successfully',
      'data' => $post,
    ], 201);
  }

  public function delete(string $id): Response
  {
    $post = Post::findOne($id);
    if (!$post) return Response::json(['message' => 'Post not found'], 404);

    $post->delete($id);

    return Response::json(['message' => 'Post deleted successfully']);
  }
}
