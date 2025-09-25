<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Framework\Core\Validator;
use Framework\Http\Request;
use Framework\Http\Response;

class PostController
{
  public function index(): Response
  {
    $posts = Post::findMany();

    return Response::view('posts.index', ['posts' => $posts]);
  }

  public function show(Request $req, string $id): Response
  {
    $post = Post::findOne($id);
    if (!$post)
      return Response::view('errors.404', [], 404);

    return Response::view('posts.show', ['post' => $post]);
  }

  public function create(Request $req): Response
  {
    $parsed = new Validator([
      'title' => 'string>=5<=100',
      'content' => 'string>=10',
    ])->parse($req->json());
    if (!$parsed->isValid())
      return Response::json(['errors' => $parsed->errors()], 422);

    return Response::json($parsed->data());
  }

  public function getPosts()
  {
    $posts = Post::findMany();

    return Response::json(array_map(fn ($post) => $post->toArray(), $posts));
  }
}
