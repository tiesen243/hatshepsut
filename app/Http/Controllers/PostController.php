<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Framework\Core\Validator;
use Framework\Http\Request;
use Framework\Http\Response;

class PostController
{
  public function getPosts(): Response
  {
    $posts = Post::findMany();

    return Response::json([
      'message' => 'Posts retrieved successfully',
      'data' => array_map(fn ($post) => $post->toArray(), $posts),
    ]);
  }

  public function getPost(Request $req, string $id): Response
  {
    $post = Post::findOne($id);
    if (!$post) return Response::json(['message' => 'Post not found'], 404);

    return Response::json([
      'message' => 'Post retrieved successfully',
      'data' => $post->toArray(),
    ]);
  }

  public function store(Request $req, ?string $id = null): Response
  {
    $parsed = new Validator([
      'title' => 'string>=5<=100',
      'content' => 'string>=10',
    ])->parse($req->json());
    if (!$parsed->isValid())
      return Response::json([
        'message' => 'Validation failed',
        'error' => $parsed->errors(),
      ], 400);

    $data = $parsed->data();

    $post = new Post('', $data['title'], $data['content']);
    if ($id) $post->setId($id);

    $post->save();

    return Response::json([
      'message' => 'Post created successfully',
      'data' => $post,
    ], 201);
  }

  public function delete(Request $req, string $id): Response
  {
    $post = Post::findOne($id);
    if (!$post) return Response::json(['message' => 'Post not found'], 404);

    $post->delete();

    return Response::json(['message' => 'Post deleted successfully']);
  }
}
