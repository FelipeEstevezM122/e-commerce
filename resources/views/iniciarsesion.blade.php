@extends('layouts.app')

@section('titulo', 'Iniciar Sesión')

@section('contenido')

    <div class="min-h-screen flex items-center justify-center p-6">

        <div
            class="w-full max-w-[95vw] min-h-[90vh] grid md:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700">

            <!-- PANEL IZQUIERDO -->
            <div
                class="hidden md:flex flex-col justify-center bg-gradient-to-br from-[#111111] via-[#1b803a] to-[#22C55E] p-12 text-white relative overflow-hidden">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-black/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-5xl font-black tracking-tight mb-4">
                        CASA<span class="text-white">TEK</span>
                    </h1>

                    <h2 class="text-3xl font-bold mb-6">
                        Seguridad Inteligente para tu Hogar y Empresa
                    </h2>

                    <p class="text-white/90 leading-relaxed mb-8">
                        Accede a tu cuenta para gestionar productos, pedidos,
                        compras y servicios de seguridad electrónica,
                        telecomunicaciones y domótica.
                    </p>

                    <div class="space-y-4">

                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-shield-halved text-2xl"></i>
                            <span>Protección garantizada</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-camera text-2xl"></i>
                            <span>Sistemas CCTV de última generación</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-wifi text-2xl"></i>
                            <span>Redes y conectividad profesional</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-house-signal text-2xl"></i>
                            <span>Automatización y Smart Home</span>
                        </div>

                    </div>

                </div>

            </div>

            <!-- PANEL DERECHO -->
            <div class="bg-white dark:bg-gray-800 p-8 md:p-12">

                <div class="max-w-md mx-auto">

                    <div class="text-center mb-8">

                        <div class="flex justify-center mb-4">
                            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fa-solid fa-user-shield text-4xl text-[#1b803a]"></i>
                            </div>
                        </div>

                        <h2 class="text-3xl font-black text-gray-900 dark:text-white">
                            Iniciar Sesión
                        </h2>

                        <p class="text-gray-500 dark:text-gray-300 mt-2">
                            Bienvenido nuevamente a CASATEK
                        </p>

                    </div>

                    <form class="space-y-6">

                        <!-- CORREO -->
                        <div>

                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                                Correo Electrónico
                            </label>

                            <div class="relative">

                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>

                                <input type="email" placeholder="correo@ejemplo.com"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">

                            </div>

                        </div>

                        <!-- CONTRASEÑA -->
                        <div>

                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                                Contraseña
                            </label>

                            <div class="relative">

                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>

                                <input type="password" placeholder="********"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">

                            </div>

                        </div>

                        <!-- RECORDAR -->
                        <div class="flex items-center justify-between text-sm">

                            <label class="flex items-center gap-2 text-gray-600 dark:text-gray-300">

                                <input type="checkbox">

                                Recordarme

                            </label>

                            <a href="/recuperar_contraseña" class="text-[#22C55E] hover:underline font-semibold">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <!-- BOTÓN -->
                        <button type="submit"
                            class="w-full bg-[#1b803a] hover:bg-[#22C55E] text-white py-3 rounded-xl font-bold tracking-wide shadow-lg transition-all duration-300 hover:scale-[1.02]">

                            INICIAR SESIÓN

                        </button>

                        <!-- REGISTRO -->
                        <div class="text-center pt-4">

                            <p class="text-gray-600 dark:text-gray-300">

                                ¿No tienes cuenta?

                                <a href="{{ route('registro') }}" class="text-[#22C55E] font-bold hover:underline">
                                    Regístrate aquí
                                </a>
                            </p>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection