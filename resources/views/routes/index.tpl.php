@extends('routes._layout')

@section('title')
  Vanilla JS Example -
@endsection

@section('head')
  @vite(['resources/js/index.ts'])
@endsection

@section('content')
  <main class="container py-4" id="root"></main>
@endsection
