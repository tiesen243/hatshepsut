@extends('layouts.main')

@section('description')
  List of all blog posts.
@endsection

@section('content')
  <main class="container py-4">
    <div class="mb-4 flex items-center justify-between">
      <h1 class="text-3xl font-bold">Posts</h1>
      <a
        href="/posts/create"
        class="focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50 inline-flex h-9 shrink-0 items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-medium whitespace-nowrap transition-all outline-none focus-visible:ring-[3px] disabled:pointer-events-none disabled:opacity-50 has-[>svg]:px-3 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
      >
        Create Post
      </a>
    </div>

    @if (empty($posts))
      <p class="text-muted-foreground">No posts available.</p>
    @else
      <ul class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($posts as $post)
          <li
            class="bg-card text-card-foreground rounded-xl border py-4 shadow-xs"
          >
            <h2 class="px-4 text-xl leading-none font-semibold">
              {{ $post->getTitle() }}
            </h2>
            <small class="text-muted-foreground px-4 text-sm">
              Created at: {{ $post->getCreatedAt()->format('Y-m-d') }}
            </small>
            <hr class="my-2" />
            <p class="line-clamp-2 px-4">{{ $post->getContent() }}</p>
            <hr class="my-2" />
            <div class="px-4">
              <a
                href="/posts/{{ $post->getId() }}"
                class="text-primary hover:underline"
              >
                Read more
              </a>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </main>
@endsection
