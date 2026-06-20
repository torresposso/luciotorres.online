@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    {{-- Hero / Bio Header — full-width --}}
    <section
      class="full-width-bleed bg-primary text-white py-20 md:py-28 lg:py-36 px-6 md:px-12 lg:px-24 flex flex-col justify-center relative overflow-hidden">
      <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-secondary/5 rounded-full blur-[80px] pointer-events-none">
      </div>
      <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary/3 rounded-full blur-[60px] pointer-events-none">
      </div>

      <div class="max-w-7xl mx-auto w-full relative z-10 grid md:grid-cols-12 gap-10 md:gap-14 items-center">
        <div class="md:col-span-7 flex flex-col gap-6 reveal">
          <span
            class="text-secondary font-display font-semibold uppercase tracking-[0.2em] text-xs sm:text-sm">Biografía</span>
          <h1 class="text-4xl md:text-5xl lg:text-7xl font-display font-black text-white leading-[0.95] text-balance">
            Sobre Lucio
          </h1>
          <p class="text-primary-content/85 text-base md:text-lg leading-relaxed font-sans max-w-2xl">
            <strong>Edison Lucio Torres</strong> (Magangué, Bolívar, Colombia, 24 de noviembre de 1945) is un
            comunicador social, periodista y escritor colombiano, defensor y docente universitario de Derechos Humanos.
            Fue, junto a Carlos Gaviria y a Gustavo Petro, uno de los precandidatos inscritos de cara a las elecciones
            presidenciales de 2010 por el partido Polo Democrático Alternativo.
          </p>
        </div>
        <div class="md:col-span-5 flex justify-center md:justify-end reveal reveal-delay-200">
          <div class="relative group max-w-sm w-full">
            <div
              class="absolute -inset-2 bg-linear-to-r from-secondary/30 via-secondary/10 to-transparent rounded-3xl blur-2xl opacity-25 group-hover:opacity-40 transition duration-700">
            </div>
            <div
              class="absolute -inset-1 bg-linear-to-br from-secondary/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition duration-700">
            </div>
            <img src="/app/uploads/2025/10/lucio-torres.png" alt="Edison Lucio Torres — Periodista y escritor colombiano"
              width="500" height="500"
              class="relative rounded-2xl shadow-premium border border-white/10 group-hover:border-secondary/30 group-hover:-translate-y-2 transition-all duration-500 ease-out w-full object-cover"
              loading="eager" decoding="async" />
          </div>
        </div>
      </div>
    </section>

    {{-- Trayectoria y Lucha --}}
    <section
      class="bg-base-100 text-base-content py-16 md:py-24 lg:py-32 px-6 md:px-12 lg:px-24 relative overflow-hidden">
      <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>
      <div class="max-w-7xl mx-auto w-full grid md:grid-cols-12 gap-10 md:gap-14 lg:gap-20 items-center relative">
        <div class="md:col-span-7 order-2 md:order-1 flex flex-col gap-6 reveal">
          <span
            class="text-brand-orange-accessible font-display font-semibold uppercase tracking-[0.2em] text-xs">TRAYECTORIA</span>
          <h2
            class="text-3xl md:text-4xl lg:text-5xl font-display font-black text-base-content leading-[1.1] text-balance">
            Trayectoria y Lucha
          </h2>
          <p class="text-base-content/85 leading-relaxed font-sans">
            Perteneció a los círculos socialistas estudiantiles de finales de la década de los 70. Dirigió las jornadas
            estudiantiles de Magangué y Barranquilla. Lideró el movimiento estudiantil que luchó por el mejoramiento de
            la calidad de la educación. En septiembre fue expulsado del Liceo Vélez donde estudiaba su bachillerato. En
            1977 debió abandonar definitivamente a Magangué para radicarse en Barranquilla. Participó en el Paro Cívico
            Nacional de 1977.
          </p>
          <p class="text-base-content/85 leading-relaxed font-sans">
            En 1979, comenzó a ejercer el periodismo cuando se matriculó en la
            <a href="https://es.wikipedia.org/wiki/Universidad_Aut%C3%B3noma_del_Caribe"
              title="Universidad Autónoma del Caribe"
              class="text-brand-orange-accessible font-semibold hover:text-secondary underline underline-offset-4 decoration-brand-orange-accessible/30 hover:decoration-secondary transition-all duration-300">Universidad
              Autónoma del Caribe</a>
            en el programa de comunicación social-periodismo, donde se graduó. Trabajó en Caracol, RCN y Olímpica, y en
            diarios como El Heraldo, Diario del Caribe y La Libertad. Fue corresponsal en Barranquilla del noticiero
            <em>Promec</em>, dirigido por María Teresa Herrán. Promovió la profesionalización y los derechos de los
            periodistas.
          </p>
        </div>
        <div class="md:col-span-5 order-1 md:order-2 flex flex-col gap-6 reveal reveal-delay-200">
          <div class="relative group w-full">
            <div class="relative rounded-2xl overflow-hidden shadow-premium">
              <div
                class="absolute inset-0 bg-linear-to-t from-primary/50 via-primary/10 to-transparent z-10 pointer-events-none">
              </div>
              <img src="/app/uploads/2025/12/WhatsApp-Image-2025-11-22-at-10.56.16-AM2.jpeg"
                alt="Edison Lucio Torres en su labor periodística" width="500" height="600" loading="lazy"
                class="relative w-full object-cover bg-base-300 group-hover:scale-[1.05] transition-transform duration-700 ease-out" />
            </div>
          </div>
          <div
            class="bg-primary text-white p-6 rounded-2xl shadow-premium border border-white/5 group hover:border-secondary/20 hover:-translate-y-1 transition-all duration-300">
            <h3 class="text-lg font-display font-bold text-secondary mb-1">Docente y Comunicador</h3>
            <p class="text-primary-content/70 text-sm leading-relaxed font-sans">Amplia experiencia en docencia y
              periodismo crítico.</p>
          </div>
        </div>
      </div>
    </section>

    {{-- Quote + Historia completa --}}
    <section
      class="bg-base-200 text-base-content py-16 md:py-24 lg:py-32 px-6 md:px-12 lg:px-24 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>
      <div class="max-w-4xl mx-auto w-full flex flex-col gap-12 relative">
        <div class="reveal">
          <blockquote
            class="relative pl-16 pr-8 py-8 italic font-display text-2xl md:text-3xl lg:text-4xl text-base-content leading-[1.3] text-balance">
            <span
              class="absolute left-0 top-0 text-7xl md:text-8xl text-secondary/20 font-serif leading-none select-none"
              aria-hidden="true">&ldquo;</span>
            <span class="relative z-10">La Gran Colombia se forja desde tu corazón, desde el Yo Soy.</span>
          </blockquote>
        </div>

        <div class="flex flex-col gap-6 font-sans reveal reveal-delay-200">
          <p class="text-base-content/85 leading-relaxed text-base md:text-lg">
            Luego se destacó en la construcción de opinión pública crítica y proactiva desde la radio barranquillera. En
            1991 impulsó activamente la consulta popular de la Ad-M19 para escoger como candidato a la alcaldía de
            Barranquilla al padre
            <a href="https://es.wikipedia.org/wiki/Bernardo_Hoyos_Montoya" title="Bernardo Hoyos Montoya"
              class="text-brand-orange-accessible font-semibold hover:text-secondary underline underline-offset-4 decoration-brand-orange-accessible/30 hover:decoration-secondary transition-all duration-300">Bernardo
              Hoyos Montoya</a>.
            Fue uno de los fundadores del Movimiento Ciudadano. En 1996 fue candidato al senado pero renunció para apoyar
            a Laura García Vda. de Pizarro.
          </p>
          <p class="text-base-content/85 leading-relaxed text-base md:text-lg">
            En el 2001, luego de sentirse frustrado por el proyecto político de Barranquilla, regresó a Cartagena. Cursó
            Altos Estudios en derechos humanos, historia nacional y la especialización en periodismo de investigación con
            la Universidad de Antioquia y Universidad Nacional. En la heroica está desarrollando una lucha por los
            derechos humanos de la población más empobrecida, desde donde nace
            <strong class="text-base-content font-bold">La Revolución de la Esperanza</strong>. Para el año 2009
            decidió promover su campaña como precandidato presidencial de la República de Colombia para las consultas
            internas de su partido Polo Democrático Alternativo.
          </p>
        </div>

        <div class="flex justify-center pt-4 reveal reveal-delay-300">
          <a href="{{ home_url('/') }}"
            class="group/link inline-flex items-center gap-2.5 text-brand-orange-accessible hover:text-secondary font-display font-bold text-sm transition-colors duration-300 py-3 px-2">
            <span class="inline-block transform group-hover/link:-translate-x-1.5 transition-transform duration-300 will-change-transform" aria-hidden="true">&larr;</span>
            Volver al inicio
          </a>
        </div>
      </div>
    </section>
  @endwhile
@endsection
