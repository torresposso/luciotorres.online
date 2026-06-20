<!-- Drawer Side Component -->
<div class="drawer-side z-100">
    <label for="main-drawer" aria-label="Cerrar navegación" class="drawer-overlay bg-black/60 backdrop-blur-sm"></label>
    <div
        class="p-6 w-full min-h-full bg-primary border-l border-white/10 text-white flex flex-col gap-8 shadow-2xl justify-between">
        <div class="flex flex-col gap-8">
            {{-- Header del Drawer --}}
            <div class="flex justify-between items-center">
                <a class="brand font-display font-extrabold text-2xl tracking-tight text-white hover:opacity-90 transition-opacity"
                    href="{{ home_url('/') }}">
                    <span class="text-secondary">LUCIO</span>TORRES
                </a>
                <label for="main-drawer"
                    class="btn btn-circle bg-transparent border-none shadow-none text-white/70 hover:text-secondary hover:bg-white/5 hover:rotate-90 transition-all duration-300"
                    aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </label>
            </div>



            {{-- Buscador Móvil Integrado --}}
            <form role="search" aria-label="Búsqueda en el sitio" method="get" action="{{ home_url('/') }}"
                class="flex items-center bg-white/5 border border-white/10 rounded-2xl px-4 text-white gap-2 w-full focus-within:border-secondary/50 transition-colors min-h-[44px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="search" placeholder="Buscar..." name="s"
                    class="bg-transparent border-none text-sm w-full focus:outline-none focus:ring-0 placeholder:text-white/30"
                    value="{{ get_search_query() }}" />
            </form>

            {{-- Menú móvil dinámico --}}
            @if (has_nav_menu('primary_navigation'))
                <nav class="w-full flex flex-col items-center" aria-label="Mobile Navigation">
                    {!! wp_nav_menu([
                        'theme_location' => 'primary_navigation',
                    'menu_class' =>
                        'menu menu-vertical items-center text-center text-xl font-display font-bold gap-4 text-white/90 w-full [&_li]:flex [&_li]:justify-center [&_li]:items-center [&_li]:w-full [&_a]:relative [&_a]:inline-flex [&_a]:justify-center [&_a]:items-center [&_a]:text-center [&_a]:w-auto [&_a]:px-4 [&_a]:min-h-[44px] [&_a]:transition-all [&_a]:duration-300 [&_a:hover]:text-secondary [&_a:hover]:bg-transparent [&_a:hover]:scale-[1.06] [&_a::after]:content-[\'\'] [&_a::after]:absolute [&_a::after]:bottom-[-2px] [&_a::after]:left-1/4 [&_a::after]:w-1/2 [&_a::after]:h-[2px] [&_a::after]:bg-secondary [&_a::after]:scale-x-0 [&_a::after]:origin-center [&_a::after]:transition-transform [&_a::after]:duration-300 [&_a:hover::after]:scale-x-100',
                        'container' => false,
                        'echo' => false,
                    ]) !!}
                </nav>
            @endif

        </div>

        {{-- Footer del Drawer con Redes y CTA --}}
        <div class="mt-auto border-t border-white/10 pt-6 flex flex-col gap-6">
            <div class="flex gap-5 items-center justify-center">
                {{-- YouTube --}}
                <a href="https://www.youtube.com/@EdisonLucioTorres" target="_blank"
                    class="text-white/60 hover:text-secondary hover:-translate-y-1 transition-all duration-300 min-h-[44px] min-w-[44px] inline-flex items-center justify-center" aria-label="YouTube">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.107C19.52 3.5 12 3.5 12 3.5s-7.52 0-9.388.556a3.003 3.003 0 0 0-2.11 2.107C0 8.028 0 12 0 12s0 3.972.502 5.837a3.003 3.003 0 0 0 2.11 2.107C4.48 20.5 12 20.5 12 20.5s7.52 0 9.388-.556a3.003 3.003 0 0 0 2.11-2.107C24 15.972 24 12 24 12s0-3.972-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                </a>
                {{-- X (Twitter) --}}
                <a href="https://twitter.com/luciotorres_o" target="_blank"
                    class="text-white/60 hover:text-secondary hover:-translate-y-1 transition-all duration-300 min-h-[44px] min-w-[44px] inline-flex items-center justify-center" aria-label="X (Twitter)">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                {{-- Facebook --}}
                <a href="https://www.facebook.com/edisonluciotorres" target="_blank"
                    class="text-white/60 hover:text-secondary hover:-translate-y-1 transition-all duration-300 min-h-[44px] min-w-[44px] inline-flex items-center justify-center" aria-label="Facebook">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
            </div>
            <a href="#contacto" onclick="document.getElementById('main-drawer').checked = false;"
                class="btn btn-secondary text-white rounded-2xl w-full text-center hover:scale-[1.02] transition-transform">
                Apoyar Propuesta
            </a>
        </div>
    </div>
</div>
