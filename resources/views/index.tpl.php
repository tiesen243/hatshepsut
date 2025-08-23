@extends('_layout')

@section('head')
  @vite(['resources/js/index.ts'])
@endsection

@section('content')
  <main class="container py-4">
    <div
      className="rounded-lg border bg-card p-4 text-card-foreground shadow-md"
    >
      <h1 className="text-2xl font-bold">Hello, Vanilla!</h1>
      <p id="counter-value" className="mt-2 text-lg">You clicked 1 times</p>
      <button
        id="counter-button"
        className="mt-4 h-9 rounded bg-primary px-4 text-primary-foreground hover:bg-primary/90 focus:outline-none"
      >
        Click me
      </button>
    </div>
  </main>
@endsection
