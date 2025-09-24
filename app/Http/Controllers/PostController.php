<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Framework\Http\Response;

class PostController
{
  public function index()
  {
    $posts = Post::findMany();

    return Response::view('posts.index', ['posts' => $posts]);
  }

  public function show($req, string $id)
  {
    $post = Post::findOne($id);
    if (!$post) {
      return Response::view('errors.404', [], 404);
    }

    return Response::view('posts.show', ['post' => $post]);
  }

  public function getPosts()
  {
    $posts = Post::findMany();

    return Response::json(array_map(fn ($post) => $post->toArray(), $posts));
  }
}
