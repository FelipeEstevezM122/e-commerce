<!-- resources/views/index.blade.php -->

<!-- 1. Heredamos la estructura del layout base -->
@extends('layouts.app')

<!-- 2. Definimos el título de esta pestaña -->
@section('titulo', 'Inicio')

<!-- 3. Metemos el contenido dentro de la sección que espera el layout -->
@section('contenido')
    
    <!-- Sección de Bienvenida / Banner -->
    <section class="bg-gradient-to-r from-gray-900 to-black text-white rounded-2xl p-8 md:p-12 shadow-md text-center md:text-left mb-12">
        <h1 class="text-3xl md:text-5xl font-bold mb-4 tracking-tight">
            Bienvenidos a <span class="text-[#22C55E]">CASATEK</span>
        </h1>
        <p class="text-gray-400 text-sm md:text-base max-w-xl mb-6">
            Tu tienda de confianza para tecnología, componentes y automatización. Explora nuestro catálogo con los mejores precios del mercado.
        </p>
        <button class="bg-[#22C55E] hover:bg-green-600 text-white font-bold px-6 py-3 rounded-lg transition-colors shadow-sm">
            Ver Productos
        </button>
    </section>

    <!-- Espacio para el listado de productos -->
    <section>
        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2 border-gray-200">
            Productos Destacados
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <p class="text-gray-500 text-sm italic col-span-full">Los productos se cargarán dinámicamente aquí...</p>
        </div>
    </section>

@endsection <!-- Aquí termina la sección -->