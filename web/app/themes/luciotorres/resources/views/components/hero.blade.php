{{--
  Hero — The masthead.
  A full-viewport political poster: the masthead rule on top, the name as the
  title, the photo as the cover image, the manifesto tagline types itself out.
  Every element reads as a printed broadsheet, not a landing page.
--}}
<section class="full-width-bleed bg-primary text-white relative overflow-hidden min-h-[100svh] flex flex-col">

    {{-- Subtle background ambient: one Wayuu motif, one orange wash — never multiple blur circles --}}
    <x-wayuu-motif
        class="absolute top-6 right-6 md:top-10 md:right-10 w-28 h-28 md:w-44 md:h-44 text-secondary/[0.08] pointer-events-none drift-slow" />

    <div class="absolute inset-0 pointer-events-none"
        style="background: radial-gradient(ellipse at 88% 50%, oklch(from var(--color-brand-orange) l c h / 0.07), transparent 60%);">
    </div>

    {{-- Top masthead rule: the editorial stamp that recurs throughout the page --}}
    <div class="relative z-10 max-w-7xl w-full mx-auto px-6 md:px-12 lg:px-24 pt-6 md:pt-8">
        <div class="masthead-rule text-white/70">
            <div class="masthead-rule__left">
                <span>Edición MMXXVI</span>
                <span class="hidden md:inline">·</span>
                <span class="hidden md:inline">Caribe Colombiano</span>
            </div>
            <div class="masthead-rule__center text-white hidden md:block">
                <span class="text-secondary">LUCIO</span> TORRES
            </div>
            <div class="masthead-rule__right">
                <span class="hidden md:inline">N° 46</span>
                <span class="hidden md:inline">·</span>
                <span>Magangué · Cartagena</span>
            </div>
        </div>
    </div>

    {{-- Main hero content — fills the remaining viewport --}}
    <div
        class="relative z-10 max-w-7xl w-full mx-auto px-6 md:px-12 lg:px-24 pt-12 md:pt-16 lg:pt-20 pb-16 md:pb-24 flex-1 flex flex-col justify-center">

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

            {{-- Left: masthead name + manifesto (7 cols) --}}
            <div class="lg:col-span-7 flex flex-col gap-7 md:gap-8">

                {{-- Draw-in orange rule (the brand's editorial signature) --}}
                <div class="h-[3px] w-20 md:w-28 bg-secondary draw-rule" aria-hidden="true"></div>

                {{-- H1 — the masthead. Each word gets its own cinematic reveal. --}}
                <h1
                    class="font-display font-black text-white leading-[0.86] tracking-[-0.035em] text-[clamp(3.5rem,11vw,8.5rem)] text-balance">
                    <span class="block name-reveal-first">Lucio</span>
                    <span class="block text-secondary name-reveal-second">Torres.</span>
                </h1>

                {{-- Manifesto tagline — typewriter effect, no JS --}}
                <p
                    class="font-display font-extrabold text-primary-content leading-[1] tracking-[-0.02em] text-[clamp(2rem,5.5vw,3.75rem)] text-balance max-w-xl">
                    <span class="typewriter">Pan con Paz.</span>
                </p>

                {{-- Position statement — a single dignified paragraph --}}
                <p class="font-sans text-primary-content/85 text-base md:text-lg leading-relaxed max-w-xl text-pretty">
                    Comunicador Social, periodista de investigación y docente universitario.
                    <span class="text-white font-semibold">46 años</span> forjando una mirada crítica
                    sobre la realidad colombiana y promoviendo la propuesta cívica unificadora de
                    <span class="text-secondary font-semibold">Pan con Paz</span>.
                </p>

                {{-- CTAs — dignified, no longer "Sígueme" --}}
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    <a href="#sumate"
                        class="btn btn-secondary rounded-2xl text-white font-display font-bold px-8 py-4 text-base hover:scale-[1.03] transition-transform duration-300 shadow-premium border-0 min-h-[48px]">
                        Firmar el compromiso
                        <span class="ml-2" aria-hidden="true">→</span>
                    </a>
                    <a href="/biografia/"
                        class="group inline-flex items-center gap-2 text-white font-display font-semibold px-6 py-4 rounded-2xl border border-white/20 hover:border-secondary hover:text-secondary transition-all duration-300 min-h-[48px]">
                        Mi historia
                        <span class="inline-block group-hover:translate-x-1 transition-transform duration-300"
                            aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            {{-- Right: portrait (5 cols) — cinematic, scale-in --}}
            <div class="lg:col-span-5 relative">
                <div class="relative photo-reveal">

                    {{-- A vertical "running head" beside the photo (left edge of column) --}}
                    <div class="hidden md:flex absolute -left-8 top-0 bottom-0 flex-col items-center justify-between py-4 text-secondary/60"
                        aria-hidden="true">
                        <span class="block w-px h-full bg-secondary/30"></span>
                        <span
                            class="font-display font-black text-[0.6rem] tracking-[0.4em] uppercase -rotate-90 whitespace-nowrap">Caribe
                            · 1945</span>
                    </div>

                    {{-- Thin orange frame-offset beside the photo (poster layering) --}}
                    <div class="absolute top-0 -right-3 w-3 h-24 md:h-32 bg-secondary rounded-full" aria-hidden="true">
                    </div>

                    <div class="relative rounded-2xl overflow-hidden shadow-cinema border border-white/5">
                        <img src="/app/uploads/2025/12/WhatsApp-Image-2025-11-22-at-10.56.16-AM2.jpeg"
                            alt="Edison Lucio Torres, periodista y defensor de Derechos Humanos frente a la bandera de Colombia"
                            width="600" height="750" fetchpriority="high" loading="eager" decoding="async"
                            class="w-full h-full object-cover aspect-[4/5]" />
                    </div>

                    {{-- Citation stamp at the bottom of the photo --}}
                    <div
                        class="absolute -bottom-6 -left-3 md:-left-6 bg-base-100 text-base-content p-4 md:p-5 rounded-2xl shadow-premium max-w-[240px]">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="block w-2 h-2 rounded-full bg-secondary" aria-hidden="true"></span>
                            <span
                                class="text-[0.6rem] font-sans font-bold tracking-[0.2em] uppercase text-brand-orange-accessible">Nacido
                                en</span>
                        </div>
                        <div class="font-display font-black text-base md:text-lg leading-tight">Magangué, Bolívar</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom: scroll indicator (the editorial "continúa" cue) --}}
    <div
        class="relative z-10 max-w-7xl w-full mx-auto px-6 md:px-12 lg:px-24 pb-6 md:pb-8 hidden md:flex items-center justify-between text-white/50">
        <div class="running-head">Continúa · Manifiesto</div>
        <div class="scroll-indicator flex flex-col items-center gap-2" aria-hidden="true">
            <span class="text-[0.6rem] font-sans font-bold tracking-[0.3em] uppercase">Scroll</span>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M19 12l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
</section>
