{{--
  Sala de Prensa — Chapter V.
  The latest investigations. Editorial list, not a card grid.
  Each entry: date column, content column. Real journalism, real dates.
--}}
@php
  $press = new \WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
    'ignore_sticky_posts' => true,
  ]);
@endphp

<section id="sala-de-prensa" class="bg-base-100 text-base-content relative overflow-hidden">
  <div class="max-w-6xl mx-auto w-full px-6 md:px-12 lg:px-24 py-24 md:py-32 lg:py-40 relative">

    {{-- Chapter mark --}}
    <div class="flex items-end gap-6 md:gap-8 mb-10 md:mb-14 reveal-immediate">
      <span class="chapter-numeral" aria-hidden="true">V</span>
      <div class="flex flex-col gap-2 pb-2 md:pb-3">
        <div class="chapter-rule" aria-hidden="true"></div>
        <span class="running-head text-brand-orange-accessible">Sala de Prensa</span>
      </div>
    </div>

    {{-- Section title + intro --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12 md:mb-16">
      <h2 class="font-display font-black text-base-content leading-[1.02] tracking-[-0.035em] text-[clamp(2rem,4.5vw,3.5rem)] text-balance max-w-2xl">
        Últimas
        <span class="text-brand-orange-accessible italic">investigaciones.</span>
      </h2>
      <p class="font-sans text-base-content/70 text-base md:text-lg max-w-md text-pretty">
        Análisis, reportajes y columnas de opinión desde el Caribe colombiano.
      </p>
    </div>

    @if ($press->have_posts())
      {{-- Editorial list — date column + content column, no card grid --}}
      <ol class="border-t border-base-300 reveal-immediate-d200">
        @php $i = 0; @endphp
        @while ($press->have_posts())
          @php $press->the_post(); $i++; @endphp
          <li class="border-b border-base-300 group">
            <a href="{{ get_permalink() }}"
              class="grid grid-cols-[auto_1fr] md:grid-cols-[6rem_1fr_auto] gap-6 md:gap-10 items-baseline py-8 md:py-10 transition-colors duration-300 hover:bg-base-200/50 -mx-4 px-4 rounded-2xl">

              {{-- Date column (Roman-numeral style index + date) --}}
              <div class="flex flex-col items-start">
                <span class="font-display font-black text-brand-orange-accessible text-3xl md:text-4xl leading-none tracking-[-0.04em]">
                  {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                </span>
                <time datetime="{{ get_the_date('c') }}"
                  class="font-sans text-[0.65rem] font-bold tracking-[0.2em] uppercase text-base-content/70 mt-2">
                  {{ get_the_date('M Y') }}
                </time>
              </div>

              {{-- Content column --}}
              <div class="flex flex-col gap-2">
                <h3 class="font-display font-black text-base-content leading-[1.1] tracking-[-0.02em] text-[clamp(1.5rem,2.8vw,2.25rem)] text-balance group-hover:text-brand-orange-accessible transition-colors duration-300">
                  {{ get_the_title() }}
                </h3>
                @php
                  $excerpt = has_excerpt()
                    ? get_the_excerpt()
                    : wp_trim_words(wp_strip_all_tags(get_the_content()), 28, '…');
                @endphp
                <p class="font-sans text-base-content/70 text-base md:text-lg leading-relaxed max-w-2xl text-pretty line-clamp-2">
                  {{ wp_strip_all_tags($excerpt) }}
                </p>
              </div>

              {{-- Arrow --}}
              <div class="hidden md:flex items-center self-center text-base-content/40 group-hover:text-brand-orange-accessible group-hover:translate-x-2 transition-all duration-300" aria-hidden="true">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
            </a>
          </li>
        @endwhile
      </ol>
    @else
      <p class="font-sans text-base-content/60 italic">Aún no hay investigaciones publicadas.</p>
    @endif

    @php wp_reset_postdata(); @endphp

    {{-- Footer of section: link to archive + press contact --}}
    <div class="mt-12 md:mt-16 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 reveal-immediate-d400">
      <a href="{{ get_permalink(get_option('page_for_posts')) ?: '/' }}"
        class="group inline-flex items-center gap-2 text-base-content font-display font-bold text-base md:text-lg transition-colors duration-300 hover:text-brand-orange-accessible">
        Ver archivo completo
        <span class="inline-block group-hover:translate-x-1.5 transition-transform duration-300 will-change-transform" aria-hidden="true">→</span>
      </a>
      <p class="font-sans text-sm text-base-content/60">
        Para interviews y prensa:
        <a href="mailto:prensa@luciotorres.online" class="editorial-link">prensa@luciotorres.online</a>
      </p>
    </div>
  </div>
</section>
