{{--
  Trayectoria — Chapter IV.
  46 años forged in radio, prensa escrita, universidad pública.
  No card grid. The number is the frontispiece, the roles are the colophon.
--}}
<section id="trayectoria" class="bg-primary text-white relative overflow-hidden">
  {{-- Faint Wayuu motif as deep-background texture --}}
  <x-wayuu-motif class="absolute -bottom-16 -right-16 w-72 h-72 text-secondary/[0.04] pointer-events-none" />

  <div class="max-w-7xl mx-auto w-full px-6 md:px-12 lg:px-24 py-24 md:py-32 lg:py-40 relative">

    {{-- Chapter mark --}}
    <div class="flex items-end gap-6 md:gap-8 mb-12 md:mb-16 reveal-immediate">
      <span class="chapter-numeral chapter-numeral--dark" aria-hidden="true">IV</span>
      <div class="flex flex-col gap-2 pb-2 md:pb-3">
        <div class="chapter-rule chapter-rule--dark" aria-hidden="true"></div>
        <span class="running-head running-head--inverted">Trayectoria</span>
      </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-12 items-start">
      {{-- Left: The 46-year statement, treated as a book frontispiece --}}
      <div class="lg:col-span-5 flex flex-col gap-4 reveal-immediate-d200">
        <div class="flex items-baseline gap-3">
          <span class="font-display font-black text-brand-orange text-[clamp(6rem,14vw,11rem)] leading-[0.82] tracking-[-0.05em] tabular-nums">46</span>
          <span class="font-display font-semibold text-primary-content/80 text-2xl md:text-3xl leading-tight">años.</span>
        </div>
        <p class="font-sans text-primary-content/85 text-base md:text-lg leading-relaxed max-w-md text-pretty">
          Forjado en la radio, la prensa escrita y la universidad pública. Una vida dedicada a la investigación y a la defensa de los derechos fundamentales.
        </p>
      </div>

      {{-- Right: A single editorial line listing the four roles and the media --}}
      <div class="lg:col-span-7 flex flex-col gap-8 reveal-immediate-d400">
        <p class="font-display font-black text-white leading-[1.08] tracking-[-0.025em] text-[clamp(1.75rem,3.8vw,2.75rem)] text-balance">
          Comunicador social, periodista, docente y escritor.
        </p>

        <p class="font-sans text-primary-content/85 text-base md:text-lg leading-relaxed max-w-2xl text-pretty">
          Trabajó en
          <span class="text-brand-orange font-semibold">Caracol</span>,
          <span class="text-brand-orange font-semibold">RCN</span>,
          <span class="text-brand-orange font-semibold">Olímpica</span> y en diarios como
          <span class="text-brand-orange font-semibold">El Heraldo</span>,
          <span class="text-brand-orange font-semibold">Diario del Caribe</span> y
          <span class="text-brand-orange font-semibold">La Libertad</span>. Fue corresponsal en Barranquilla del noticiero
          <em class="text-brand-orange font-semibold not-italic">Promec</em>, dirigido por María Teresa Herrán.
        </p>

        <a href="/biografia/"
          class="group inline-flex items-center gap-2 text-brand-orange font-display font-bold text-base md:text-lg transition-colors duration-300 mt-2 py-2 min-h-[48px]">
          Conocer mi historia completa
          <span class="inline-block group-hover:translate-x-1.5 transition-transform duration-300 will-change-transform" aria-hidden="true">→</span>
        </a>
      </div>
    </div>
  </div>
</section>
