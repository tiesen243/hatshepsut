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
            class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm"
          >
            <div
              class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 border-b px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6"
            >
              <h2 class="text-lg leading-none font-semibold">
                {{ $post->getTitle() }}
              </h2>
              <small class="text-muted-foreground text-sm">
                Created at: {{ $post->getCreatedAt()->format('Y-m-d') }}
              </small>
              <form
                method="POST"
                action="/api/posts/{{ $post->getId() }}/delete"
                class="col-start-2 row-span-2 row-start-1 self-start justify-self-end"
              >
                <button
                  type="submit"
                  class="focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-background hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 inline-flex size-9 shrink-0 items-center justify-center gap-2 rounded-md border text-sm font-medium whitespace-nowrap shadow-xs transition-all outline-none focus-visible:ring-[3px] disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-x-icon lucide-x"
                  >
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                  </svg>
                </button>
              </form>
            </div>

            <div class="px-6">
              <p class="line-clamp-2">{{ $post->getContent() }}</p>
            </div>

            <div class="flex items-center justify-end px-6 [.border-t]:pt-6">
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
