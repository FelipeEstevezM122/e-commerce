<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <div class="space-y-16 font-['Poppins']">

        <!-- SECCIÓN 1: BIENVENIDA A CASATEK Y PROPUESTA DE VALOR -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Texto Corporativo e Iconos Informativos -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold text-gray-400 tracking-widest uppercase flex items-center gap-2">
                        <span class="w-4 h-[1px] bg-gray-400"></span> Conócenos
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Bienvenido a <span class="text-[#1b803a]">Casatek</span>
                    </h2>
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-300 space-y-4 leading-relaxed font-medium">
                    <p>
                        En Casatek nos dedicamos a ofrecer soluciones integrales en sistemas de seguridad electrónica,
                        telecomunicaciones y automatización. Nos esmeramos por brindar tranquilidad a hogares y empresas a
                        través de tecnología de vanguardia y un soporte técnico especializado y confiable.
                    </p>
                    <p>
                        Trabajamos de la mano con marcas líderes a nivel mundial para garantizar instalaciones robustas,
                        duraderas y adaptadas a las necesidades específicas de cada uno de nuestros clientes.
                    </p>
                </div>

                <!-- Grilla de Beneficios / Iconos rápidos -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div
                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="p-2 bg-green-50 text-[#1b803a] rounded-lg">
                            ...
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">Garantía Real</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-300">Soporte post-venta</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="p-2 bg-green-50 text-[#1b803a] rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">Alta Tecnología</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-300">Marcas certificadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagen Corporativa / Ilustrativa de Bienvenida -->
            <div class="relative flex justify-center">
                <div class="absolute inset-0 bg-[#1b803a]/5 rounded-3xl transform rotate-3 scale-102 -z-10"></div>
                <img src="{{ asset('images/corporativo-casatek.jpg') }}" alt="Soluciones Casatek"
                    class="w-full max-w-md h-[320px] object-cover rounded-3xl shadow-md border border-gray-100">
            </div>
        </section>


        <!-- SECCIÓN 2: BANNER PRINCIPAL CON CARRUSEL INTERACTIVO -->
        <section
            class="relative bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm min-h-[420px] flex items-center">

            <!-- Indicadores Laterales Numéricos Interactivos (01 al 04) -->
            <div class="hidden lg:flex flex-col gap-4 absolute left-6 z-20 text-xs font-bold text-gray-400">
                <button id="btn-slide-0"
                    class="text-gray-900 dark:text-white border-l-2 border-gray-900 dark:border-white pl-2 focus:outline-none transition-all duration-300 dynamic-indicator">01</button>
                <button id="btn-slide-0"
                    class="text-gray-900 dark:text-white border-l-2 border-gray-900 dark:border-white pl-2 focus:outline-none transition-all duration-300 dynamic-indicator">02</button>
                <button id="btn-slide-0"
                    class="text-gray-900 dark:text-white border-l-2 border-gray-900 dark:border-white pl-2 focus:outline-none transition-all duration-300 dynamic-indicator">03</button>
                <button id="btn-slide-0"
                    class="text-gray-900 dark:text-white border-l-2 border-gray-900 dark:border-white pl-2 focus:outline-none transition-all duration-300 dynamic-indicator">04</button>
            </div>

            <!-- CONTENEDOR DE DIAPOSITIVAS -->
            <div class="relative w-full h-full">

                <!-- Slide 01: Cámaras de Seguridad -->
                <div
                    class="slide-item transition-all duration-700 transform opacity-100 scale-100 flex flex-col md:flex-row items-center justify-between p-6 md:p-12 w-full relative">
                    <div class="space-y-4 md:max-w-md z-10 lg:ml-12 text-center md:text-left">
                        <div class="relative inline-block">
                            <h1
                                class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white relative z-10 leading-none tracking-tight">
                                Seguridad<br><span class="text-[#22C55E]">Siempre</span>
                            </h1>
                        </div>
                        <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase pt-2">
                            La tecnología tu mejor aliada / Marcas líderes en telecomunicaciones
                        </p>
                        <div class="pt-2">
                            <a href="{{ url('/productos') }}"
                                class="inline-block bg-[#1b803a] hover:bg-green-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-md tracking-wider uppercase">
                                Ver Cámaras
                            </a>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-center items-center mt-6 md:mt-0 h-72 z-10">
                        <img src="{{ asset('images/banner-camaras.png') }}" alt="Cámaras de Seguridad"
                            class="max-w-full max-h-full object-contain drop-shadow-2xl">
                    </div>
                </div>

                <!-- Slide 02: Sistemas de Alarmas Inteligentes -->
                <div
                    class="slide-item transition-all duration-700 transform opacity-0 scale-95 absolute inset-0 hidden w-full">
                    <div class="flex flex-col md:flex-row items-center justify-between p-6 md:p-12 w-full h-full">
                        <div class="space-y-4 md:max-w-md z-10 lg:ml-12 text-center md:text-left">
                            <div class="relative inline-block">
                                <h1
                                    class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white relative z-10 leading-none tracking-tight">
                                    Seguridad<br><span class="text-[#22C55E]">Siempre</span>
                                </h1>
                            </div>
                            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase pt-2">
                                Protección total contra intrusos / Notificaciones directas a tu celular
                            </p>
                            <div class="pt-2">
                                <a href="{{ url('/productos?categoria=alarmas') }}"
                                    class="inline-block bg-[#1b803a] hover:bg-green-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-md tracking-wider uppercase">
                                    Ver Alarmas
                                </a>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-center items-center mt-6 md:mt-0 h-72 z-10">
                            <img src="{{ asset('images/banner-alarmas.png') }}" alt="Alarmas inteligentes"
                                class="max-w-full max-h-full object-contain drop-shadow-2xl">
                        </div>
                    </div>
                </div>

                <!-- Slide 03: Conectividad y Redes -->
                <div
                    class="slide-item transition-all duration-700 transform opacity-0 scale-95 absolute inset-0 hidden w-full">
                    <div class="flex flex-col md:flex-row items-center justify-between p-6 md:p-12 w-full h-full">
                        <div class="space-y-4 md:max-w-md z-10 lg:ml-12 text-center md:text-left">
                            <div class="relative inline-block">
                                <h1
                                    class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white relative z-10 leading-none tracking-tight">
                                    Seguridad<br><span class="text-[#22C55E]">Siempre</span>
                                </h1>
                            </div>
                            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase pt-2">
                                Routers Rompemuros y Switches / Equipamiento Mikrotik y TP-Link
                            </p>
                            <div class="pt-2">
                                <a href="{{ url('/productos?categoria=redes') }}"
                                    class="inline-block bg-[#1b803a] hover:bg-green-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-md tracking-wider uppercase">
                                    Ver Redes
                                </a>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-center items-center mt-6 md:mt-0 h-72 z-10">
                            <img src="{{ asset('images/banner-redes.png') }}" alt="Redes de comunicación"
                                class="max-w-full max-h-full object-contain drop-shadow-2xl">
                        </div>
                    </div>
                </div>

                <!-- Slide 04: Automatización y Domótica -->
                <div
                    class="slide-item transition-all duration-700 transform opacity-0 scale-95 absolute inset-0 hidden w-full">
                    <div class="flex flex-col md:flex-row items-center justify-between p-6 md:p-12 w-full h-full">
                        <div class="space-y-4 md:max-w-md z-10 lg:ml-12 text-center md:text-left">
                            <div class="relative inline-block">
                                <span
                                    class="absolute -top-12 left-0 text-5xl md:text-7xl font-black text-gray-100/70 tracking-tighter select-none pointer-events-none"></span>
                                <h1
                                    class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white relative z-10 leading-none tracking-tight">
                                    Seguridad<br><span class="text-[#22C55E]">Siempre</span>
                                </h1>
                            </div>
                            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase pt-2">
                                Controla luces, accesos y energía / Compatible con Alexa y Google Assistant
                            </p>
                            <div class="pt-2">
                                <a href="{{ url('/productos?categoria=domotica') }}"
                                    class="inline-block bg-[#1b803a] hover:bg-green-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-md tracking-wider uppercase">
                                    Ver Domótica
                                </a>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-center items-center mt-6 md:mt-0 h-72 z-10">
                            <img src="{{ asset('images/banner-domotica.png') }}" alt="Automatización del hogar"
                                class="max-w-full max-h-full object-contain drop-shadow-2xl">
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <!-- SECCIÓN 3: CATEGORÍAS EN TENDENCIA -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-[#1b803a] tracking-widest uppercase">Explora</span>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Marcas en Tendencia
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Categoría 1 -->
                <a href="{{ url('/productos?categoria=cctv') }}"
                    class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-all flex flex-col items-center space-y-3">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-green-50 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#1b803a] transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3
                        class="text-xs font-bold text-gray-800 dark:text-white group-hover:text-[#1b803a] transition-colors">
                        Cámaras y CCTV</h3>
                </a>

                <!-- Categoría 2 -->
                <a href="{{ url('/productos?categoria=alarmas') }}"
                    class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-all flex flex-col items-center space-y-3">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-green-50 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#1b803a] transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3
                        class="text-xs font-bold text-gray-800 dark:text-white group-hover:text-[#1b803a] transition-colors">
                        Sistemas de Alarma</h3>
                </a>

                <!-- Categoría 3 -->
                <a href="{{ url('/productos?categoria=redes') }}"
                    class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-all flex flex-col items-center space-y-3">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-green-50 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#1b803a] transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 110-6 3 3 0 010 6z" />
                        </svg>
                    </div>
                    <h3
                        class="text-xs font-bold text-gray-800 dark:text-white group-hover:text-[#1b803a] transition-colors">
                        Redes y Conectividad</h3>
                </a>

                <!-- Categoría 4 -->
                <a href="{{ url('/productos?categoria=domotica') }}"
                    class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-all flex flex-col items-center space-y-3">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-green-50 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#1b803a] transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3
                        class="text-xs font-bold text-gray-800 dark:text-white group-hover:text-[#1b803a] transition-colors">
                        Domótica / Smart Home</h3>
                </a>
            </div>
        </section>

    </div>

    <!-- SCRIPT DEL CARRUSEL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.slide-item');
            const buttons = document.querySelectorAll('.dynamic-indicator');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                if (index >= slides.length) index = 0;
                if (index < 0) index = slides.length - 1;

                slides.forEach((slide) => {
                    slide.classList.remove('opacity-100', 'scale-100', 'relative', 'flex', 'flex-col', 'md:flex-row', 'items-center', 'justify-between');
                    slide.classList.add('opacity-0', 'scale-95', 'absolute', 'hidden');
                });
                buttons.forEach((btn) => {
                    btn.classList.remove('text-gray-900', 'border-l-2', 'border-gray-900');
                    btn.classList.add('text-gray-400');
                });

                slides[index].classList.remove('opacity-0', 'scale-95', 'absolute', 'hidden');
                slides[index].classList.add('opacity-100', 'scale-100', 'relative', 'flex', 'flex-col', 'md:flex-row', 'items-center', 'justify-between');

                buttons[index].classList.remove('text-gray-400');
                buttons[index].classList.add('text-gray-900', 'dark:text-white', 'border-l-2', 'border-gray-900', 'dark:border-white');

                currentSlide = index;
            }

            function startAutoSlide() {
                clearInterval(slideInterval);
                slideInterval = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 5000);
            }

            buttons.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    showSlide(index);
                    startAutoSlide();
                });
            });

            startAutoSlide();
        });
    </script>
@endsection