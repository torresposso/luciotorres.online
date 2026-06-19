<footer class="content-info bg-primary text-white/60 border-t border-white/10 py-12 px-6 text-center">
    <div class="max-w-7xl mx-auto w-full flex flex-col items-center gap-6">
        <a href="/"
            class="font-display font-black text-2xl text-white tracking-wide uppercase hover:text-secondary transition-colors duration-300">
            Lucio Torres
        </a>
        <p class="text-sm text-white/60 max-w-md leading-relaxed font-sans">
            Periodismo de opinión independiente y de alto impacto, comprometido con la verdad y la propuesta cívica
            unificadora de <strong>Pan con Paz</strong>.
        </p>
        <div class="w-full border-t border-white/5 my-2"></div>
        <p class="text-xs text-white/40 font-sans">
            &copy; {{ date('Y') }} {{ get_bloginfo('name') }}.
            {{ __('Todos los derechos reservados.', 'luciotorres') }}
        </p>

    </div>
</footer>
