<!doctype html>
<html <?php language_attributes(); ?> data-theme="luciotorres">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" media="print" onload="this.media='all'">
    <noscript>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap">
    </noscript>
    <link rel="icon" type="image/svg+xml" href="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/favicon.svg') }}">
    <?php do_action('get_header'); ?>
    <?php wp_head(); ?>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
  </head>

  <body <?php body_class(); ?> class="bg-base-100 font-sans antialiased text-base-content min-h-screen flex flex-col">
    <?php wp_body_open(); ?>

    <div id="app" class="flex flex-col min-h-screen">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Ir al contenido principal', 'luciotorres') }}
      </a>

      @include('sections.header')

      <main id="main" class="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar max-w-6xl w-full mx-auto px-4 pb-8">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
