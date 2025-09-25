@extends('layouts.main')

@section('title', '404 Not Found')
@section('description', 'The page you are looking for could not be found.')

@section('content')
<main class="container py-4 flex flex-col min-h-dvh gap-4 items-center justify-center">
  <h1 class="text-5xl font-bold">404 Not Found</h1>
  <p class="text-lg mb-8">The page you are looking for could not be found.</p>
  <a href="/" class="hover:underline underline-offset-4">Take me home</a>
</main>
@endsection
