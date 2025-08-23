@extends('routes._layout')

@section('title')
  Vite + PHP + React -
@endsection

@section('head')
  @vite(['resources/js/index.tsx'])
@endsection

@section('content')
  <main id="root" class="container"></main>
@endsection
