@extends('routes._layout')

@section('title')
  Vite + PHP + Vue -
@endsection

@section('head')
  @vite(['resources/js/index.ts'])
@endsection

@section('content')
  <main id="root" class="container"></main>
@endsection
