<div
    class="navbar sticky top-0 z-50 bg-primary backdrop-blur-md border-b border-white/5 py-4 px-6 md:px-12 transition-all duration-300">
    <div class="navbar-start">
        {{-- Brand Logo --}}
        <a class="brand font-display font-extrabold text-2xl tracking-tight text-white hover:opacity-90 transition-opacity min-h-[44px] inline-flex items-center"
            href="{{ home_url('/') }}">
            <span class="text-secondary">LUCIO</span>TORRES
        </a>
    </div>


    {{-- Menú de escritorio --}}
    <div class="navbar-center hidden lg:flex">
        @if (has_nav_menu('primary_navigation'))
            <nav aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                {!! wp_nav_menu([
                    'theme_location' => 'primary_navigation',
                    'menu_class' =>
                        'flex gap-8 text-md font-black tracking-wide uppercase text-white/90 [&_a:hover]:text-secondary [&_a]:transition-colors',
                    'container' => false,
                    'echo' => false,
                ]) !!}
            </nav>
        @endif
    </div>

    {{-- CTA de Conversión e Inline Search --}}
    <div class="navbar-end gap-3">
        {{-- Buscador Escritorio Inline --}}
        <form role="search" aria-label="Búsqueda en el sitio" method="get" action="{{ home_url('/') }}"
            class="hidden lg:flex items-center bg-white/5 border border-white/10 rounded-2xl px-3 text-white gap-2 w-40 focus-within:w-56 focus-within:border-secondary/50 transition-all duration-300 min-h-[44px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/50" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="search" placeholder="Buscar..." name="s"
                class="bg-transparent border-none text-xs w-full focus:outline-none focus:ring-0 placeholder:text-white/30"
                value="{{ get_search_query() }}" />
        </form>

        <a href="#sumate"
            class="btn btn-sm btn-secondary border-none text-white font-display font-semibold px-6 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 hover:-translate-y-0.5 hidden sm:inline-flex">
            Firmar compromiso
        </a>

        {{-- Botón hamburguesa para abrir el Drawer móvil --}}
        @if (has_nav_menu('primary_navigation'))
            <label for="main-drawer"
                class="btn btn-ghost lg:hidden text-white p-2 ml-1 drawer-button min-h-[44px] min-w-[44px]" aria-label="Abrir navegación">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </label>
        @endif
    </div>

</div>
