<section id="contacto" class="bg-primary text-white w-full py-16 md:py-24 lg:py-32 px-6 md:px-12 lg:px-24 flex flex-col items-center gap-12 text-center relative overflow-hidden">
  <!-- Soft background gradient light -->
  <div class="absolute inset-0 bg-linear-to-b from-brand-midnight to-brand-midnight/90 opacity-95 pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

  <!-- Header -->
  <div class="max-w-2xl flex flex-col items-center gap-4 relative z-10 reveal">
    <span class="text-secondary font-display font-bold text-xs uppercase tracking-widest bg-secondary/15 py-1.5 px-4 rounded-full">
      Únete al Movimiento
    </span>
    <h2 class="text-3xl md:text-5xl font-display font-black text-white leading-tight">
      Súmate a la Gran Colombia
    </h2>
    <p class="text-slate-300 text-base leading-relaxed font-sans max-w-xl">
      Regístrate para mantenerte en contacto, recibir las investigaciones especiales y apoyar activamente la propuesta país del comité cívico.
    </p>
  </div>

  <!-- Vertical Elegant Form -->
  <form class="w-full max-w-md flex flex-col gap-4 relative z-10 mt-2 reveal reveal-delay-200" onsubmit="event.preventDefault(); alert('¡Gracias por unirte!');">
    <!-- Full Name Input -->
    <div class="flex flex-col gap-1.5 items-start">
      <label for="name" class="text-xs text-slate-400 font-sans tracking-wide">Nombre Completo</label>
      <input 
        type="text" 
        id="name" 
        name="name" 
        placeholder="Ingresa tu nombre y apellido" 
        autocomplete="name"
        required 
        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-slate-500 outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-300 font-sans text-sm" 
      />
    </div>

    <!-- Email Input -->
    <div class="flex flex-col gap-1.5 items-start">
      <label for="email" class="text-xs text-slate-400 font-sans tracking-wide">Correo Electrónico</label>
      <input 
        type="email" 
        id="email" 
        name="email" 
        placeholder="nombre@ejemplo.com" 
        autocomplete="email"
        required 
        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-slate-500 outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-300 font-sans text-sm" 
      />
    </div>

    <!-- Submit Button -->
    <button 
      type="submit" 
      class="btn btn-secondary w-full text-white rounded-xl py-4 hover:scale-[1.02] shadow-lg shadow-secondary/15 hover:bg-brand-orange-light transition-all duration-300 font-display font-bold text-sm tracking-wider uppercase mt-2 border-none"
    >
      Suscribirse al Cambio
    </button>
  </form>
</section>
