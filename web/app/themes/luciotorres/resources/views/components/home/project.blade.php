{{--
  La Gran Colombia — Chapter III.
  The documentary is the dominant image. The manifesto quote is the spine.
  Chapter numeral as the voice element. Single CTA.
--}}
<section id="gran-colombia" class="bg-primary text-white relative overflow-hidden">

  <div class="max-w-7xl mx-auto w-full px-6 md:px-12 lg:px-24 py-20 md:py-28 lg:py-36 relative">

    {{-- Chapter mark --}}
    <div class="flex items-end gap-6 md:gap-8 mb-12 md:mb-16 reveal-immediate">
      <span class="chapter-numeral chapter-numeral--dark" aria-hidden="true">III</span>
      <div class="flex flex-col gap-2 pb-2 md:pb-3">
        <div class="chapter-rule chapter-rule--dark" aria-hidden="true"></div>
        <span class="running-head running-head--inverted">Iniciativa de Nación</span>
      </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

      {{-- Left: title + manifesto (6 cols) --}}
      <div class="lg:col-span-6 flex flex-col gap-8 reveal-immediate-d200">

        <h2 class="font-display font-black text-white leading-[0.95] tracking-[-0.035em] text-[clamp(2.5rem,7vw,5.5rem)] text-balance">
          La Gran
          <span class="block text-secondary">Colombia.</span>
        </h2>

        <p class="font-sans text-primary-content/90 text-base md:text-lg leading-relaxed max-w-xl text-pretty">
          Esta es la oportunidad para comenzar un buen día y nacer de nuevo en el Ser y crear el Gran Macondo que está dentro de mí. —Se dijo a sí mismo con su frente sudorosa y su camisilla empapada en este amanecer Caribe lleno de luz y calor.
        </p>

        <blockquote class="relative pl-6 md:pl-8 font-display text-primary-content text-xl md:text-2xl leading-[1.3] tracking-[-0.01em] max-w-xl text-balance">
          <span class="absolute left-0 top-0 text-secondary/40 text-5xl md:text-6xl font-serif leading-none select-none" aria-hidden="true">&ldquo;</span>
          <span class="relative z-10">La Gran Colombia se forja desde tu corazón, desde el Yo Soy. El verdadero voto no ocurre en las urnas: ocurre en la conciencia.</span>
        </blockquote>

        <a href="/la-gran-colombia/"
          class="btn btn-secondary rounded-2xl text-white font-display font-bold px-8 py-4 text-base hover:scale-[1.03] transition-transform duration-300 shadow-premium border-0 mt-2 min-h-[48px]">
          Explorar la iniciativa
          <span class="ml-2" aria-hidden="true">→</span>
        </a>
      </div>

      {{-- Right: the documentary video poster (6 cols) — full cinematic, not a card --}}
      <div class="lg:col-span-6 relative reveal-immediate-d400">
        <a href="https://www.youtube.com/watch?v=oawg0bpWsZM" target="_blank" rel="noopener" aria-label="Reproducir documental: El despertar de la conciencia"
          class="group block relative rounded-2xl overflow-hidden shadow-cinema border border-white/5">

          <div class="relative aspect-video">
            <img
              src="/app/uploads/2025/10/foto-la-gran-colombia.jpg"
              alt="Documental: El despertar de la conciencia y la propuesta de La Gran Colombia"
              width="800" height="450" loading="lazy" decoding="async"
              class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
            />

            {{-- Cinematic dim layer — derives from brand midnight, no hardcoded colors --}}
            <div class="absolute inset-0 cinematic-overlay group-hover:opacity-80 transition-opacity duration-500"></div>

            {{-- Center play affordance — bigger, more poster-like --}}
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="relative">
                <div class="absolute inset-0 rounded-full bg-secondary/30 blur-2xl scale-150 group-hover:scale-[2] transition-transform duration-700" aria-hidden="true"></div>
                <div class="relative w-24 h-24 md:w-28 md:h-28 rounded-full bg-secondary text-white flex items-center justify-center shadow-cinema group-hover:scale-110 transition-transform duration-500">
                  <svg class="w-9 h-9 md:w-10 md:h-10 ml-1" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                </div>
              </div>
            </div>

            {{-- Lower-third title --}}
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 flex flex-col gap-2">
              <span class="text-secondary text-[0.65rem] md:text-xs font-sans font-bold tracking-[0.3em] uppercase">Documental · 2025</span>
              <h3 class="font-display font-black text-white text-2xl md:text-4xl leading-[1.05] tracking-[-0.02em] text-balance">
                El despertar de la conciencia
              </h3>
            </div>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>
