@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <section class="max-w-4xl mx-auto py-16 px-4 text-center space-y-6">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
            Bienvenido a Casatek
        </h1>
        <p class="text-gray-500 dark:text-gray-300 max-w-xl mx-auto">
            Soluciones de seguridad electrónica y tecnología para tu hogar o empresa.
        </p>
        <img src="{{ asset('images/imagen1.jpg') }}" alt="Casatek"
             class="mx-auto w-full max-w-md rounded-lg border border-gray-200 dark:border-gray-700">
        <a href="{{ route('productos') }}"
           class="inline-block mt-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:border-[#1b803a] hover:text-[#1b803a]">
            Ver productos
        </a>
    </section>
@endsection
