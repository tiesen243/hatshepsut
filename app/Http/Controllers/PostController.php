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

    return Response::view('routes.posts.index', ['posts' => $posts]);
  }

  public function show(Request $req, string $id): Response
  {
    $post = Post::findOne($id);
    if (!$post) {
      return Response::view('errors.404', [], 404);
    }

    return Response::view('routes.posts.show', ['post' => $post]);
  }

  public function create(): Response
  {
    return Response::view('routes.posts.create');
  }

  public function store(Request $req): Response
  {
    $parsed = new Validator([
      'title' => 'string>=5<=100',
      'content' => 'string>=10',
    ])->parse($req->input());
    if (!$parsed->isValid()) {
      return Response::json(['errors' => $parsed->errors()], 422);
    }

    $data = $parsed->data();
    $post = new Post('', $data['title'], $data['content']);
    $post->save();

    return Response::redirect('/posts');
  }
}
