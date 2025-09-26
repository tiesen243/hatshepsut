<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Site Metadata -->
    <title>@yield('title', 'Hatshepsut')</title>
    <meta
      name="description"
      content="@yield('description', 'A modern PHP web application framework that blends a robust backend with a streamlined frontend build process powered by Vite.')"
    />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link
      rel="canonical"
      href="@yield('url', 'https://hatshepsut.onrender.com')"
    />
    <meta name="author" content="Tiesen" />
    <meta
      name="keywords"
      content="Hatshepsut, PHP, Vite, Framework, Web Development"
    />

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Hatshepsut')" />
    <meta
      property="og:description"
      content="@yield('description', 'A modern PHP web application framework that blends a robust backend with a streamlined frontend build process powered by Vite.')"
    />
    <meta property="og:type" content="website" />
    <meta
      property="og:url"
      content="@yield('url', 'https://hatshepsut.onrender.com')"
    />
    <meta
      property="og:image"
      content="@yield('image', 'https://tiesen.id.vn/api/og?title=Hatshepsut&description=A%20modern%20PHP%20web%20application%20framework%20that%20blends%20a%20robust%20backend%20with%20a%20streamlined%20frontend%20build%20process%20powered%20by%20Vite.')"
    />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="Hatshepsut Open Graph Image" />
    <meta property="og:site_name" content="Hatshepsut" />
    <meta property="og:locale" content="en_US" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', 'Hatshepsut')" />
    <meta
      name="twitter:description"
      content="@yield('description', 'A modern PHP web application framework that blends a robust backend with a streamlined frontend build process powered by Vite.')"
    />
    <meta
      name="twitter:image"
      content="@yield('image', 'https://tiesen.id.vn/api/og?title=Hatshepsut&description=A%20modern%20PHP%20web%20application%20framework%20that%20blends%20a%20robust%20backend%20with%20a%20streamlined%20frontend%20build%20process%20powered%20by%20Vite.')"
    />
    <meta name="twitter:image:alt" content="Hatshepsut Twitter Card Image" />
    <meta name="twitter:site" content="@tiesen243" />
    <meta name="twitter:creator" content="@tiesen243" />

    <!-- Vite Assets -->
    @vite
    @vite(['resources/css/globals.css'])

    <!-- Additional Head Content -->
    @yield('head')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap"
      rel="stylesheet"
    />

    <!-- FOUC Prevention -->
    <script>
      if (
        localStorage.getItem('theme') === 'dark' ||
        (!('theme' in localStorage) &&
          window.matchMedia('(prefers-color-scheme: dark)').matches)
      )
        document.documentElement.classList.add('dark')
      else document.documentElement.classList.remove('dark')
    </script>
  </head>
  <body class="flex min-h-dvh flex-col font-sans antialiased">
    @yield('content')
  </body>
</html>
