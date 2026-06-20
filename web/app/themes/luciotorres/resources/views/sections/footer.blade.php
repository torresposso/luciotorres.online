<footer class="content-info bg-primary text-white/80 border-t border-white/10">
    <div class="max-w-7xl mx-auto w-full px-6 md:px-12 lg:px-24 py-16 md:py-20">

        {{-- Top: brand + manifesto line --}}
        <div class="flex flex-col items-center text-center gap-6 pb-12 border-b border-white/10">
            <a href="{{ home_url('/') }}"
                class="font-display font-black text-2xl md:text-3xl text-white tracking-wide uppercase hover:text-secondary transition-colors duration-300">
                <span class="text-secondary">LUCIO</span>TORRES
            </a>
            <p class="text-sm md:text-base text-white/70 max-w-xl leading-relaxed font-sans text-pretty">
                Periodismo de opinión independiente y de alto impacto, comprometido con la verdad y la propuesta cívica
                unificadora de <strong class="text-secondary font-display font-semibold">Pan con Paz</strong>.
            </p>
        </div>

        {{-- Middle: 4-column nav --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-8 py-12 border-b border-white/10">
            <div class="flex flex-col gap-3">
                <h3 class="font-display font-black text-xs tracking-[0.2em] uppercase text-white/90">Navegación</h3>
                @if (has_nav_menu('primary_navigation'))
                    {!! wp_nav_menu([
                        'theme_location' => 'primary_navigation',
                        'container' => false,
                        'menu_class' => 'flex flex-col gap-2 text-sm text-white/70 font-sans [&_a]:hover:text-secondary [&_a]:transition-colors',
                        'echo' => false,
                        'depth' => 1,
                    ]) !!}
                @endif
            </div>

            <div class="flex flex-col gap-3">
                <h3 class="font-display font-black text-xs tracking-[0.2em] uppercase text-white/90">Iniciativa</h3>
                <a href="/la-gran-colombia/" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">La Gran Colombia</a>
                <a href="/biografia/" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Biografía</a>
                <a href="{{ get_permalink(get_option('page_for_posts')) ?: '/' }}" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Sala de Prensa</a>
            </div>

            <div class="flex flex-col gap-3">
                <h3 class="font-display font-black text-xs tracking-[0.2em] uppercase text-white/90">Súmate</h3>
                <a href="#sumate" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Firmar el compromiso</a>
                <a href="/contactese/" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Contacto</a>
                <a href="mailto:prensa@luciotorres.online" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Sala de prensa</a>
            </div>

            <div class="flex flex-col gap-3">
                <h3 class="font-display font-black text-xs tracking-[0.2em] uppercase text-white/90">Legal</h3>
                <a href="{{ home_url('/politica-de-privacidad') }}" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Política de privacidad</a>
                <a href="{{ home_url('/terminos') }}" class="text-sm text-white/70 hover:text-secondary transition-colors font-sans">Términos</a>
            </div>
        </div>

        {{-- Bottom: copyright + manifesto tag --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-8 text-xs text-white/50 font-sans">
            <p>&copy; {{ date('Y') }} {{ get_bloginfo('name') }}. {{ __('Todos los derechos reservados.', 'luciotorres') }}</p>
            <p class="font-display font-bold tracking-[0.2em] uppercase text-[0.65rem]">
                <span class="text-secondary">Pan</span> con <span class="text-secondary">Paz</span>
            </p>
        </div>
    </div>
</footer>
