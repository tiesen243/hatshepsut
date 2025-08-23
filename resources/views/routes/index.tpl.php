@extends('routes._layout')

@section('title')
  Vanilla JS Example -
@endsection

@section('head')
  @vite(['resources/js/index.ts'])
@endsection

@section('content')
  <main class="container py-4">
    <div class="rounded-lg border bg-card p-4 text-card-foreground shadow-md">
      <h1 class="text-2xl font-bold">Hello, Vanilla!</h1>
      <p id="counter-value" class="mt-2 text-lg">You clicked 1 times</p>
      @include(
        'components.ui.button',
        ['slot' => 'Lick me', 'attributes' => ['id' => 'counter-button']]
      )
    </div>
  </main>
@endsection
