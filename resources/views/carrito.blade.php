@extends('layouts.app')

@section('titulo', 'Carrito')

@section('contenido')

<div class="max-w-[95vw] mx-auto py-10 font-['Poppins']">

    <!-- TÍTULO -->
    <div class="mb-10">
        <span class="text-xs font-bold text-[#1b803a] tracking-widest uppercase">
            Tu Compra
        </span>

        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            Mi <span class="text-[#22C55E]">Carrito</span>
        </h1>

        <p class="text-gray-500 dark:text-gray-300 mt-2">
            Revisa tus productos antes de finalizar la compra.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- PRODUCTOS -->
        <div class="lg:col-span-2 space-y-6">

            <!-- PRODUCTO 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-lg p-6">

                <div class="flex flex-col md:flex-row gap-6">

                    <img
                        src="{{ asset('images/banner-camaras.png') }}"
                        alt="Producto"
                        class="w-44 h-44 object-contain rounded-2xl bg-gray-50 dark:bg-gray-700 p-4">

                    <div class="flex-1">

                        <div class="flex justify-between items-start">

                            <div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">
                                    Cámara Hikvision IP 4MP
                                </h2>

                                <p class="text-gray-500 dark:text-gray-300 mt-2">
                                    Sistema profesional de videovigilancia para hogares y empresas.
                                </p>
                            </div>

                            <button class="text-red-500 hover:text-red-600 text-xl">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">

                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">

                                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                    -
                                </button>

                                <span class="px-6 font-bold dark:text-white">
                                    1
                                </span>

                                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                    +
                                </button>

                            </div>

                            <span class="text-3xl font-black text-[#22C55E]">
                                Bs. 850
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PRODUCTO 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-lg p-6">

                <div class="flex flex-col md:flex-row gap-6">

                    <img
                        src="{{ asset('images/banner-domotica.png') }}"
                        alt="Producto"
                        class="w-44 h-44 object-contain rounded-2xl bg-gray-50 dark:bg-gray-700 p-4">

                    <div class="flex-1">

                        <div class="flex justify-between items-start">

                            <div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">
                                    Smart Home Controller
                                </h2>

                                <p class="text-gray-500 dark:text-gray-300 mt-2">
                                    Automatiza luces, puertas y dispositivos inteligentes.
                                </p>
                            </div>

                            <button class="text-red-500 hover:text-red-600 text-xl">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">

                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">

                                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                    -
                                </button>

                                <span class="px-6 font-bold dark:text-white">
                                    1
                                </span>

                                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                    +
                                </button>

                            </div>

                            <span class="text-3xl font-black text-[#22C55E]">
                                Bs. 650
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- GUARDADOS -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-lg p-8">

                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-4">
                    Guardados para Después
                </h2>

                <p class="text-gray-500 dark:text-gray-300">
                    Aquí aparecerán los productos que quieras comprar más adelante.
                </p>

            </div>

        </div>

        <!-- RESUMEN -->
        <div>

            <div class="sticky top-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-2xl p-8">

                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-8">
                    Resumen del Pedido
                </h2>

                <div class="space-y-5">

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">
                            Subtotal
                        </span>

                        <span class="font-bold dark:text-white">
                            Bs. 1500
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">
                            Envío
                        </span>

                        <span class="font-bold text-green-600">
                            Gratis
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">
                            Impuestos
                        </span>

                        <span class="font-bold dark:text-white">
                            Bs. 0
                        </span>
                    </div>

                    <hr class="dark:border-gray-700">

                    <div class="flex justify-between items-center">

                        <span class="text-xl font-black text-gray-900 dark:text-white">
                            Total
                        </span>

                        <span class="text-4xl font-black text-[#22C55E]">
                            Bs. 1500
                        </span>

                    </div>

                </div>

                <button
                    class="mt-8 w-full bg-[#1b803a] hover:bg-[#22C55E] text-white py-4 rounded-2xl font-black text-lg shadow-lg transition-all duration-300 hover:scale-[1.02]">

                    <i class="fa-solid fa-lock mr-2"></i>
                    FINALIZAR COMPRA

                </button>

                <a href="{{ url('/productos') }}"
                   class="block text-center mt-4 border-2 border-[#1b803a] text-[#1b803a] hover:bg-[#1b803a] hover:text-white py-3 rounded-2xl font-bold transition-all duration-300">

                    Seguir Comprando

                </a>

            </div>

        </div>

    </div>

</div>

@endsection