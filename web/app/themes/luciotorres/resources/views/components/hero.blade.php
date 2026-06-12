<!-- Hero section component - Option B (Editorial Asymmetry, Lucio Focus) -->
<section
    class="bg-primary text-white w-full py-16 md:py-24 lg:py-32 min-h-[calc(100vh-80px)] px-6 md:px-12 lg:px-24 flex flex-col justify-center relative overflow-hidden">

    <!-- Soft background gradient light -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[120px] pointer-events-none">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-secondary/3 rounded-full blur-[80px] pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto w-full flex flex-col gap-2 relative z-10">
        <!-- Eyebrow -->
        {{-- <div class="animate-fade-in-up">
            <span class="text-secondary font-display font-bold uppercase tracking-widest text-xs">Periodismo
                Independiente y Opinión</span>
        </div> --}}

        <!-- Huge Banner Title -->
        <h1
            class="hidden md:block  md:text-9xl font-display font-black leading-none text-accent tracking-[-0.03em] uppercase animate-fade-in-up animation-delay-100">
            Lucio Torres
        </h1>

        <!-- Asymmetric two-column content area -->
        <div class="grid md:grid-cols-12 gap-12 items-center my-4">
            <!-- Left Campaign content -->
            <div class="md:col-span-7 flex flex-col gap-2 items-start order-2 md:order-1">
                <h2
                    class="text-2xl md:text-4xl font-display font-extrabold leading-tight text-white animate-fade-in-up animation-delay-200">
                    Una voz independiente frente al poder. Periodista y defensor de Derechos Humanos.
                </h2>
                <p
                    class="text-slate-300 text-base md:text-lg leading-relaxed max-w-xl animate-fade-in-up animation-delay-300">
                    Comunicador Social, periodista de investigación y docente universitario. He dedicado mi vida a
                    investigar la verdad, defender los derechos fundamentales y promover la propuesta cívica unificadora
                    de <strong>Pan con Paz</strong>.
                </p>
                <div class="flex flex-row gap-4 w-full md:w-auto mt-2 animate-fade-in-up animation-delay-400">
                    <a href="/biografia/"
                        class="btn btn-secondary flex-1 md:flex-none md:w-auto px-8 text-white rounded-2xl hover:scale-105 transition-all duration-300 shadow-lg shadow-secondary/15">
                        Mi Historia
                    </a>
                    <a href="#contacto"
                        class="btn btn-outline flex-1 md:flex-none md:w-auto border-white/20 text-white rounded-2xl px-6 hover:bg-white hover:text-primary hover:border-white transition-all duration-300">
                        Pan con Paz
                    </a>
                </div>
            </div>

            <!-- Right Portrait content -->
            <div
                class="md:col-span-5 flex justify-center md:justify-end animate-fade-in-up animation-delay-300 order-1 md:order-2">
                <div class="relative group max-w-sm w-full">
                    <!-- Glow backdrop -->
                    <div
                        class="absolute -inset-1 bg-linear-to-r from-secondary/30 to-secondary/5 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition duration-500">
                    </div>
                    <img src="/app/uploads/2025/10/lucio-torres-enhanced.png" alt="Lucio Torres"
                        class="relative rounded-2xl shadow-premium border border-white/5 group-hover:border-white/10 group-hover:-translate-y-1.5 transition-all duration-500 ease-out w-full object-cover" />
                </div>
            </div>
        </div>
    </div>
</section>
