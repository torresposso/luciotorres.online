{{--
  Súmate — Chapter VI.
  Civic conversion. A direct, dignified form. No friction.
  The form posts to admin-post.php?action=lucio_firma; the handler
  (registered in setup.php) creates a "firma" custom post type.
--}}
<section id="sumate" class="bg-base-200 text-base-content relative overflow-hidden">
  {{-- Background ambient --}}
  <x-wayuu-motif class="absolute top-12 right-6 md:top-16 md:right-12 w-32 h-32 md:w-48 md:h-48 text-brand-orange-accessible/[0.05] pointer-events-none" />

  <div class="max-w-6xl mx-auto w-full px-6 md:px-12 lg:px-24 py-24 md:py-32 lg:py-40 relative">

    <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

      {{-- Left: the manifesto + chapter (5 cols) --}}
      <div class="lg:col-span-5 flex flex-col gap-8 lg:sticky lg:top-32">

        <div class="flex items-end gap-6 md:gap-8 reveal-immediate">
          <span class="chapter-numeral" aria-hidden="true">VI</span>
          <div class="flex flex-col gap-2 pb-2 md:pb-3">
            <div class="chapter-rule" aria-hidden="true"></div>
            <span class="running-head text-brand-orange-accessible">Súmate</span>
          </div>
        </div>

        <h2 class="font-display font-black text-base-content leading-[1.02] tracking-[-0.035em] text-[clamp(2.25rem,5vw,4rem)] text-balance reveal-immediate-d200">
          Únete a
          <span class="text-brand-orange-accessible italic">Pan con Paz.</span>
        </h2>

        <p class="font-sans text-base-content/85 text-base md:text-lg leading-relaxed max-w-md text-pretty reveal-immediate-d300">
          Suma tu nombre al compromiso cívico por una Colombia con Pan y con Paz. Sin spam, sin promesas vacías — solo una alternativa real.
        </p>

        <ul class="flex flex-col gap-3 reveal-immediate-d400">
          <li class="flex items-start gap-3 text-base-content/80">
            <span class="mt-1.5 block w-1.5 h-1.5 rounded-full bg-brand-orange-accessible flex-shrink-0" aria-hidden="true"></span>
            <span class="font-sans text-sm md:text-base">Recibe las investigaciones antes que nadie</span>
          </li>
          <li class="flex items-start gap-3 text-base-content/80">
            <span class="mt-1.5 block w-1.5 h-1.5 rounded-full bg-brand-orange-accessible flex-shrink-0" aria-hidden="true"></span>
            <span class="font-sans text-sm md:text-base">Acceso a la sala de prensa y material verificable</span>
          </li>
          <li class="flex items-start gap-3 text-base-content/80">
            <span class="mt-1.5 block w-1.5 h-1.5 rounded-full bg-brand-orange-accessible flex-shrink-0" aria-hidden="true"></span>
            <span class="font-sans text-sm md:text-base">Convocatorias a eventos y encuentros en el Caribe</span>
          </li>
        </ul>
      </div>

      {{-- Right: the form (7 cols) --}}
      <div class="lg:col-span-7 reveal-immediate-d300">
        <form id="signup-form" action="{{ admin_url('admin-post.php') }}" method="post"
          class="bg-base-100 border border-base-300 rounded-2xl p-6 md:p-10 flex flex-col gap-5 shadow-premium"
          novalidate>

          @php wp_nonce_field('lucio_firma', 'lucio_firma_nonce'); @endphp
          <input type="hidden" name="action" value="lucio_firma">

          <div class="grid sm:grid-cols-2 gap-5">
            <div class="flex flex-col gap-2">
              <label for="firma-name" class="font-sans text-xs font-bold tracking-[0.2em] uppercase text-base-content/70">
                Nombre <span class="text-brand-orange-accessible" aria-hidden="true">*</span>
              </label>
              <input id="firma-name" name="name" type="text" required autocomplete="name"
                class="bg-base-100 border border-base-300 rounded-2xl px-4 py-3 font-sans text-base text-base-content placeholder:text-base-content/40 focus:border-brand-orange-accessible focus:outline-none focus:ring-2 focus:ring-brand-orange-accessible/20 transition-all min-h-[48px]" />
            </div>

            <div class="flex flex-col gap-2">
              <label for="firma-email" class="font-sans text-xs font-bold tracking-[0.2em] uppercase text-base-content/70">
                Correo <span class="text-brand-orange-accessible" aria-hidden="true">*</span>
              </label>
              <input id="firma-email" name="email" type="email" required autocomplete="email"
                class="bg-base-100 border border-base-300 rounded-2xl px-4 py-3 font-sans text-base text-base-content placeholder:text-base-content/40 focus:border-brand-orange-accessible focus:outline-none focus:ring-2 focus:ring-brand-orange-accessible/20 transition-all min-h-[48px]" />
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <label for="firma-city" class="font-sans text-xs font-bold tracking-[0.2em] uppercase text-base-content/70">
              Ciudad <span class="text-base-content/40 font-normal normal-case tracking-normal">(opcional)</span>
            </label>
            <input id="firma-city" name="city" type="text" autocomplete="address-level2"
              class="bg-base-100 border border-base-300 rounded-2xl px-4 py-3 font-sans text-base text-base-content placeholder:text-base-content/40 focus:border-brand-orange-accessible focus:outline-none focus:ring-2 focus:ring-brand-orange-accessible/20 transition-all min-h-[48px]" />
          </div>

          <fieldset class="flex flex-col gap-3">
            <legend class="font-sans text-xs font-bold tracking-[0.2em] uppercase text-base-content/70">
              Quiero sumarme como
            </legend>
            <div class="grid sm:grid-cols-2 gap-2">
              @php
                $roles = [
                  'ciudadano'    => ['Ciudadano', 'Apoyo la propuesta cívica'],
                  'voluntario'   => ['Voluntario', 'Quiero colaborar activamente'],
                  'prensa'       => ['Periodista aliado', 'Trabajo en medios'],
                  'donante'      => ['Donante', 'Quiero apoyar económicamente'],
                ];
              @endphp
              @foreach ($roles as $value => $role)
                <label class="flex items-start gap-3 p-3 border border-base-300 rounded-2xl cursor-pointer hover:border-brand-orange-accessible/50 has-[:checked]:border-brand-orange-accessible has-[:checked]:bg-brand-orange-accessible/[0.04] transition-all">
                  <input type="radio" name="role" value="{{ $value }}"
                    class="mt-1 w-4 h-4 accent-[var(--color-brand-orange-accessible)] cursor-pointer" {{ $loop->first ? 'checked' : '' }} />
                  <div class="flex flex-col gap-0.5">
                    <span class="font-display font-bold text-sm text-base-content">{{ $role[0] }}</span>
                    <span class="font-sans text-xs text-base-content/60">{{ $role[1] }}</span>
                  </div>
                </label>
              @endforeach
            </div>
          </fieldset>

          <div class="flex items-start gap-3 pt-2">
            <input id="firma-consent" name="consent" type="checkbox" required
              class="mt-1 w-4 h-4 accent-[var(--color-brand-orange-accessible)] cursor-pointer" />
            <label for="firma-consent" class="font-sans text-sm text-base-content/70 text-pretty">
              Acepto recibir comunicaciones de la campaña de Lucio Torres. No compartiremos tus datos. Puedes darte de baja cuando quieras.
            </label>
          </div>

          <button type="submit"
            class="btn btn-primary rounded-2xl text-white font-display font-bold text-base px-8 py-4 mt-2 min-h-[48px] border-0 shadow-premium hover:shadow-premium-hover transition-all duration-300 hover:-translate-y-0.5">
            Firmar el compromiso
            <span class="ml-2" aria-hidden="true">→</span>
          </button>
        </form>
      </div>

    </div>
  </div>
</section>
