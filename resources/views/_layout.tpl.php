<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Load fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Geist&family=Geist+Mono:wght@100..900&display=swap"
      rel="stylesheet"
    />

    @vite
    @vite(['resources/css/globals.css', 'resources/js/theme.js'])

    @yield('head')

    <title>@yield('title')</title>
  </head>

  <body class="flex min-h-dvh flex-col font-sans antialiased">
    @yield('content')
  </body>
</html>
