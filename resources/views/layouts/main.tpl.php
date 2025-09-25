<!DOCTYPE html>
<html lang="en" class="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Hatshepsut')</title>
    <meta
      name="description"
      content="@yield('description', 'A lightweight PHP MVC framework')"
    />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap"
      rel="stylesheet"
    />

    @vite
    @vite(['resources/css/globals.css'])

    @yield('head')
  </head>
  <body class="flex min-h-dvh flex-col font-sans antialiased">
    @yield('content')

    <script>
      @yield('scripts')
    </script>
  </body>
</html>
