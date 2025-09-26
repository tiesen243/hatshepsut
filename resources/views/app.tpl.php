@extends('layouts.main')

@section('head')
  @viteReactRefresh
  @vite(['resources/js/app.tsx'])
@endsection

@section('content')
  <main id="root"></main>
@endsection
