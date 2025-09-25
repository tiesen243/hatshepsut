@extends('layouts.main')

@section('description') List of all blog posts. @endsection

@section('content')
<main class="container py-4">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold">Posts</h1>
    <a href="/posts/create" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50 h-9 px-4 py-2 has-[>svg]:px-3">
      Create Post
    </a>
  </div>

  @if (empty($posts))
    <p class="text-muted-foreground">No posts available.</p>
  @else
  <ul class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-4">
    @foreach ($posts as $post)
      <li class="bg-card text-card-foreground py-4 border rounded-xl shadow-xs">
        <h2 class="text-xl font-semibold leading-none px-4">{{ $post->getTitle() }}</h2>
        <small class="text-muted-foreground text-sm px-4">Created at: {{ $post->getCreatedAt()->format('Y-m-d') }}</small>
        <hr class="my-2" />
        <p class="px-4 line-clamp-2">{{ $post->getContent() }}</p>
        <hr class="my-2" />
        <div class="px-4">
          <a href="/posts/{{ $post->getId() }}" class="text-primary hover:underline">Read more</a>
        </div>
      </li>
    @endforeach
  </ul>
  @endif
</main>
@endsection
