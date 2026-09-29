@extends('layouts.app')

@section('titulo', 'Recuperar Contraseña')

@section('contenido')

<div class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">

        <div class="text-center mb-8">

            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-key text-4xl text-[#1b803a]"></i>
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-900 dark:text-white">
                Recuperar Contraseña
            </h2>

            <p class="text-gray-500 dark:text-gray-300 mt-3">
                Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña.
            </p>

        </div>

        <form>

            <div class="mb-6">

                <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                    Correo Electrónico
                </label>

                <div class="relative">

                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>

                    <input
                        type="email"
                        placeholder="correo@ejemplo.com"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">

                </div>

            </div>

            <button
                type="submit"
                class="w-full bg-[#1b803a] hover:bg-[#22C55E] text-white py-3 rounded-xl font-bold shadow-lg transition-all duration-300">

                ENVIAR INSTRUCCIONES

            </button>

            <div class="text-center mt-6">

                <a href="/iniciarsesion"
                    class="text-[#22C55E] font-semibold hover:underline">

                    ← Volver al inicio de sesión

                </a>

            </div>

        </form>

    </div>

</div>

@endsection