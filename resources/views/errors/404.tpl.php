@extends('layouts.main')

@section('title', '404 Not Found')
@section('description', 'The page you are looking for could not be found.')

@section('content')
  <main
    class="container flex min-h-dvh flex-col items-center justify-center gap-4 py-4"
  >
    <h1 class="text-5xl font-bold">404 Not Found</h1>
    <p class="mb-8 text-lg">The page you are looking for could not be found.</p>
    <a href="/" class="underline-offset-4 hover:underline">Take me home</a>
  </main>
@endsection
