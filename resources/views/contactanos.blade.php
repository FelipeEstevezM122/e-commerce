@extends('layouts.app')
 
@section('titulo', 'Contáctanos')
 
@section('contenido')
<div class="space-y-12 font-['Poppins'] pb-12">
 
    <section class="text-center max-w-2xl mx-auto space-y-2 pt-4">
        <span class="text-xs font-bold text-[#1b803a] dark:text-[#22C55E] tracking-widest uppercase">Atención Inmediata</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
            Comunícate con <span class="text-[#1b803a] dark:text-[#22C55E]">Casatek</span>
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
            Estamos listos para asesorarte en tus proyectos de seguridad electrónica, redes y automatización. ¡Visítanos o llámanos hoy mismo!
        </p>
    </section>
 
    <!-- CONTENEDOR PRINCIPAL -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
 
        <!-- TARJETA DE INFORMACIÓN -->
        <div class="lg:col-span-4 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-8 shadow-sm flex flex-col justify-between text-center relative overflow-hidden group hover:shadow-md dark:hover:shadow-green-900/20 transition-shadow">
            <div class="absolute left-0 top-0 h-full w-[4px] bg-[#1b803a] dark:bg-[#22C55E]"></div>
 
            <div class="space-y-8 my-auto">
 
                <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight uppercase">
                    Visítanos en<br>Nuestro Showroom
                </h2>
 
                <hr class="border-gray-100 dark:border-gray-700 max-w-[80px] mx-auto">
 
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 mb-1">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white tracking-widest uppercase">Dirección</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed font-medium">
                        Calle Colombia Nro. 278<br>
                        Entre Calle Cañada Strongest y México<br>
                        Zona San Pedro, La Paz - Bolivia
                    </p>
                </div>
 
                <!-- Teléfonos -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 text-[#1b803a] dark:text-[#22C55E] mb-1">
                        <i class="fa-solid fa-phone text-base"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white tracking-widest uppercase">Teléfonos de contacto</h3>
                    <div class="text-xs text-gray-600 dark:text-gray-400 font-bold space-y-1">
                        <p class="hover:text-[#1b803a] dark:hover:text-[#22C55E] transition-colors cursor-pointer">76216837</p>
                        <p class="hover:text-[#1b803a] dark:hover:text-[#22C55E] transition-colors cursor-pointer">77297541</p>
                        <p class="text-gray-500 dark:text-gray-500">2-480648</p>
                    </div>
                </div>
 
                <!-- Email -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 mb-1">
                        <i class="fa-solid fa-envelope text-base"></i>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white tracking-widest uppercase">Email</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 font-bold hover:underline cursor-pointer">
                        casatekbolivia@gmail.com
                    </p>
                </div>
 
            </div>
        </div>
 
        <!-- MAPA -->
        <div class="lg:col-span-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-3 shadow-sm flex min-h-[450px]">
            <div class="w-full h-full rounded-2xl overflow-hidden relative border border-gray-100 dark:border-gray-700">
                <iframe
                    src="https://maps.google.com/maps?q=-16.501699447631836,-68.13500213623047&z=17&output=embed"
                    class="absolute inset-0 w-full h-full border-0 grayscale-[10%] contrast-[110%] dark:grayscale dark:brightness-75 dark:contrast-125 rounded-2xl"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
 
    </section>
 
</div>
@endsection