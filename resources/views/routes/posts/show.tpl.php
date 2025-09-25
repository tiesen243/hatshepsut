@extends('layouts.main')

@section('title') {{ $post->getTitle() }} @endsection
@section('description') {{ mb_strimwidth($post->getContent(), 0, 150, '...') }} @endsection

@section('content')
<article class="container py-4">
  <h1 class="text-3xl font-bold">{{ $post->getTitle() }}</h1>
  <small class="text-muted-foreground text-sm">Created at: {{ $post->getCreatedAt()->format('Y-m-d') }}</small>
  <hr class="my-4" />
  <div class="text-lg leading-relaxed">
    {{ $post->getContent() }}
  </div>
</article>
@endsection
