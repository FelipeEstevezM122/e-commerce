<!-- resources/views/contactanos.blade.php -->
@extends('layouts.app')

@section('titulo', 'Contáctanos')

@section('contenido')
<div class="space-y-12 font-['Poppins'] pb-12">

    <!-- ENCABEZADO DE LA SECCIÓN -->
    <section class="text-center max-w-2xl mx-auto space-y-2 pt-4">
        <span class="text-xs font-bold text-[#1b803a] tracking-widest uppercase">Atención Inmediata</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
            Comunícate con <span class="text-[#1b803a]">Casatek</span>
        </h1>
        <p class="text-sm text-gray-500 leading-relaxed font-medium">
            Estamos listos para asesorarte en tus proyectos de seguridad electrónica, redes y automatización. ¡Visítanos o llámanos hoy mismo!
        </p>
    </section>

    <!-- CONTENEDOR PRINCIPAL: INFORMACIÓN Y MAPA (Basado en la imagen image_ec9f68.jpg) -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- TARJETA DE INFORMACIÓN (Lado Izquierdo) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 p-8 shadow-sm flex flex-col justify-between text-center relative overflow-hidden group hover:shadow-md transition-shadow">
            <!-- Detalle estético en el borde lateral izquierdo -->
            <div class="absolute left-0 top-0 h-full w-[4px] bg-[#1b803a]"></div>
            
            <div class="space-y-8 my-auto">
                <!-- Título Interno -->
                <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                    Visítanos en<br>Nuestro
                </h2>

                <hr class="border-gray-100 max-w-[80px] mx-auto">

                <!-- Bloque 1: Showroom -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-500 mb-1">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 tracking-widest uppercase">Showroom</h3>
                    <p class="text-xs text-gray-600 leading-relaxed font-medium">
                        Calle Colombia Nro. 278<br>
                        Zona San Pedro, La Paz - Bolivia
                    </p>
                </div>

                <!-- Bloque 2: Teléfonos -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-50 text-[#1b803a] mb-1">
                        <i class="fa-solid fa-phone text-base"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 tracking-widest uppercase">Teléfonos de contacto</h3>
                    <div class="text-xs text-gray-600 font-bold space-y-1">
                        <p class="hover:text-[#1b803a] transition-colors cursor-pointer">76216837</p>
                        <p class="hover:text-[#1b803a] transition-colors cursor-pointer">77297541</p>
                        <p class="text-gray-500">2-480648</p>
                    </div>
                </div>

                <!-- Bloque 3: Email -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 text-blue-500 mb-1">
                        <i class="fa-solid fa-envelope text-base"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 tracking-widest uppercase">Email</h3>
                    <p class="text-xs text-gray-600 font-bold hover:underline cursor-pointer">
                        casatekbolivia@gmail.com
                    </p>
                </div>
            </div>
        </div>

        <!-- MAPA DE GOOGLE MAPS INTERACTIVO (Lado Derecho) -->
        <div class="lg:col-span-8 bg-white border border-gray-100 rounded-3xl p-3 shadow-sm flex min-h-[450px]">
            <div class="w-full h-full rounded-2xl overflow-hidden relative border border-gray-100">
                <!-- Iframe real de Google Maps apuntando a Calle Colombia, San Pedro, La Paz -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1m4!2m2!1d-68.1364!2d-16.5015!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDMwJzA1LjQiUyA2OMKwMDgnMTEuMCJX!5e0!3m2!1ses!2sbo!4v1700000000000!5m2!1ses!2sbo" 
                    class="absolute inset-0 w-full h-full border-0 grayscale-[10%] contrast-[110%] rounded-2xl" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </section>

</div>
@endsection