@extends('layouts.app')

@section('titulo', 'Nosotros')

@section('contenido')
<div class="space-y-20 font-['Poppins'] pb-12">
    <section class="text-center max-w-2xl mx-auto space-y-3 pt-4">
        <span class="text-xs font-bold text-[#22C55E] tracking-widest uppercase">Conoce nuestro equipo</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
            Sobre <span class="text-[#1b803a] dark:text-[#22C55E]">Nosotros</span>
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
            En Casatek trabajamos con pasión para ofrecer soluciones tecnológicas que protegen lo que más valoras, asegurando un entorno confiable para hogares y empresas.
        </p>
    </section>

    {{-- HISTORIA --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 order-2 lg:order-1">
            <div class="space-y-2">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 tracking-widest uppercase flex items-center gap-2">
                    <span class="w-4 h-[1px] bg-gray-400 dark:bg-gray-600"></span> Trayectoria
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Nuestra Historia
                </h2>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-4 leading-relaxed font-medium">
                <p>
                    Casatek nació con la firme convicción de transformar la manera en que las personas interactúan con la seguridad y la tecnología en sus espacios cotidianos. Identificamos la necesidad latente en el mercado de contar con sistemas de seguridad electrónica y telecomunicaciones que no solo fueran avanzados, sino también accesibles y con un soporte técnico verdaderamente confiable.
                </p>
                <p>
                    A lo largo de nuestra trayectoria, nos hemos consolidado como una empresa líder en distribución e instalación de equipamiento de última generación. Hemos expandido nuestras soluciones más allá de las alarmas tradicionales, integrando redes de conectividad robustas, control de accesos automatizado y soluciones de domótica adaptadas a las exigencias del mundo moderno.
                </p>
            </div>
        </div>

        <div class="relative flex justify-center order-1 lg:order-2">
            <div class="absolute inset-0 bg-[#1b803a]/5 dark:bg-[#22C55E]/10 rounded-3xl transform rotate-2 scale-102 -z-10"></div>
            <img src="{{ asset('images/nosotrosimg.jpg') }}" alt="Historia de Casatek"
                 class="w-full max-w-md h-[300px] object-cover rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        </div>
    </section>

    {{-- MISIÓN Y VISIÓN --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="relative flex justify-center">
            <div class="absolute inset-0 bg-[#22C55E]/5 dark:bg-[#22C55E]/10 rounded-3xl transform -rotate-2 scale-102 -z-10"></div>
            <img src="{{ asset('images/nosotrosimg2.jpg') }}" alt="Misión Casatek"
                 class="w-full max-w-md h-[300px] object-cover rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        </div>

        <div class="space-y-8">
            <div class="space-y-3">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-[#22C55E] tracking-widest uppercase">El Propósito</span>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Nuestra Misión</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">
                    Proveer tranquilidad y protección a través del diseño, distribución e implementación de sistemas integrales de seguridad electrónica y automatización. Nos comprometemos a entregar soluciones robustas alineadas perfectamente con el éxito y las necesidades específicas de cada cliente.
                </p>
            </div>

            <div class="space-y-3">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-[#22C55E] tracking-widest uppercase">El Futuro</span>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Nuestra Visión</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">
                    Ser reconocidos como el referente número uno en integración tecnológica del país, distinguiéndonos por nuestra excelencia técnica, la calidad certificada de nuestras marcas aliadas y un servicio post-venta excepcional que garantice relaciones a largo plazo.
                </p>
            </div>
        </div>
    </section>

    <!-- VALORES  -->
    <section class="space-y-8">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 tracking-widest uppercase">Pilares de Trabajo</span>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Los Valores que nos Mueven</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-md dark:hover:shadow-green-900/20 transition-all space-y-4 group">
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 text-[#1b803a] dark:text-[#22C55E] rounded-xl flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Compromiso y Garantía</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                    No solo instalamos equipos; respaldamos cada proyecto con soporte técnico dedicado y soluciones duraderas que aseguran tu inversión.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-md dark:hover:shadow-green-900/20 transition-all space-y-4 group">
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 text-[#1b803a] dark:text-[#22C55E] rounded-xl flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Innovación Tecnológica</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                    Nos mantenemos a la vanguardia probando y adoptando las mejores tecnologías globales en redes, CCTV y domótica inteligente.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-md dark:hover:shadow-green-900/20 transition-all space-y-4 group">
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 text-[#1b803a] dark:text-[#22C55E] rounded-xl flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Personal Profesional</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                    Contamos con un equipo de ingeniería capacitado y técnico listo para resolver desafíos complejos de infraestructura y conectividad.
                </p>
            </div>

        </div>
    </section>

</div>
@endsection