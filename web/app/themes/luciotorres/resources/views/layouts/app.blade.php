<!doctype html>
<html <?php language_attributes(); ?> data-theme="luciotorres">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap">
    </noscript>
    <link rel="icon" type="image/svg+xml" href="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/favicon.svg') }}">
    <meta name="description" content="Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico y mirada progresista.">
    <?php do_action('get_header'); ?>
    @include('partials.seo-head')
    <?php wp_head(); ?>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
  </head>

  <body <?php body_class(); ?> class="bg-base-100 font-sans antialiased text-base-content min-h-screen flex flex-col">
    <?php wp_body_open(); ?>

    <div class="drawer drawer-end">

      <input id="main-drawer" type="checkbox" class="drawer-toggle" />
      
      <div class="drawer-content flex flex-col min-h-screen">
        <div id="app" class="flex flex-col min-h-screen">
          <a class="sr-only focus:not-sr-only" href="#main">
            {{ __('Ir al contenido principal', 'luciotorres') }}
          </a>

          @include('sections.header')

          <main id="main" class="flex-1 {{ is_front_page() ? 'w-full' : 'max-w-6xl w-full mx-auto px-4 py-8' }}">
            @yield('content')
          </main>

          @hasSection('sidebar')
            <aside class="sidebar max-w-6xl w-full mx-auto px-4 pb-8">
              @yield('sidebar')
            </aside>
          @endif

          @include('sections.footer')
        </div>
      </div>

      {{-- Lateral (Drawer Side Component) --}}
      <x-drawer />


    </div>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
