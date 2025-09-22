<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Essential Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') Hatshepsut</title>
    <meta
      name="description"
      content="@yield('description', 'Hatshepsut: Modern web application')"
    />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Fonts: Geist & Geist Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap"
      rel="stylesheet"
    />

    <!-- Vite Assets -->
    @vite
    @viteReactRefresh
    @vite(['resources/css/globals.css', 'resources/js/main.tsx'])

    <!-- Fix Flash of Unstyled Content (FOUC) -->
    <script>
      ;(function () {
        const theme = localStorage.getItem('theme') ?? 'light'
        if (theme === 'dark') document.documentElement.classList.add('dark')
      })()
    </script>

    <!-- Additional head content from child views -->
    @yield('head')
  </head>

  <body id="root" class="flex min-h-dvh flex-col font-sans antialiased"></body>
</html>
