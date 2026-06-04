@extends('layouts.app')

@section('titulo', 'Catálogo de Productos - Casatek')

@section('contenido')
<section class="py-6 space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-5 border-gray-200">
        <div class="flex items-center gap-3 shrink-0">
            <span class="w-2 h-6 bg-[#22C55E] rounded-full"></span>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Nuestro Catálogo de Productos</h2>
        </div>
        
        <div class="relative w-full md:w-1/2 max-w-xl">
            <input type="text" 
                   placeholder="Buscar cámaras, sensores, alarmas..." 
                   class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:border-[#22C55E] focus:outline-none text-sm transition-all shadow-sm bg-gray-50/50 focus:bg-white">
            <span class="absolute left-4 top-3.5 text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        {{-- Bucle principal conectado a Cloudinary --}}
        @forelse($images ?? [] as $image)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
                
                <div class="w-full h-48 bg-gray-50 overflow-hidden relative">
                    <img src="{{ $image->cloudinary_url }}" alt="{{ $image->original_name }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 right-3 bg-gray-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                        {{ $image->format }}
                    </span>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div class="space-y-1">
                        <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">MARCA DISPOSITIVO</span>
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-[#22C55E] transition-colors">
                            {{ $image->original_name }}
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                            Descripción del componente importado desde Cloudinary. Garantía oficial de Casatek.
                        </p>
                        <div class="pt-1">
                            <span class="text-lg font-black text-gray-900">0.00 Bs.</span>
                        </div>
                    </div>

                    <a href="https://wa.me/59176216837" target="_blank" class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar por WhatsApp
                    </a>
                </div>
            </div>
            
        @empty
            {{-- DATA DE DEMOSTRACIÓN AMPLIADA (8 Productos Variados de Tecnología) --}}
            @php
                $productosDemos = [
                    [
                        'nombre' => 'Cámara de Seguridad Domo IP 4K',
                        'marca' => 'Dahua',
                        'precio' => '320.00',
                        'descripcion' => 'Visión nocturna a color, resolución de 8MP, ranura MicroSD de hasta 256GB y detección de movimiento inteligente.',
                        'img' => 'https://images.unsplash.com/photo-1557597774-9d273605dfa9?w=500'
                    ],
                    [
                        'nombre' => 'Kit Alarma Inteligente Wifi/GSM',
                        'marca' => 'Tuya Smart',
                        'precio' => '680.00',
                        'descripcion' => 'Incluye panel central, sensor de movimiento, sensor de puerta y dos controles remotos con alerta directa al celular.',
                        'img' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500'
                    ],
                    [
                        'nombre' => 'Sensor de Movimiento Infrarrojo PIR',
                        'marca' => 'Hikvision',
                        'precio' => '115.00',
                        'descripcion' => 'Inmunidad contra mascotas de hasta 24kg, alcance de cobertura de 12 metros y conexión inalámbrica de alta seguridad.',
                        'img' => 'https://images.unsplash.com/photo-1595435742656-5272d0b3fa82?w=500'
                    ],
                    [
                        'nombre' => 'Videoportero Digital Wifi HD',
                        'marca' => 'Ezviz',
                        'precio' => '450.00',
                        'descripcion' => 'Audio bidireccional integrado, cámara con lente gran angular, campana inalámbrica para interiores y resistencia a lluvia IP65.',
                        'img' => 'https://images.unsplash.com/photo-1521714161819-155349685541?w=500'
                    ],
                    [
                        'nombre' => 'Cerradura Digital Inteligente Biométrica',
                        'marca' => 'Intelbras',
                        'precio' => '890.00',
                        'descripcion' => 'Acceso mediante huella dactilar, contraseña numérica, tarjeta RFID o llave física de emergencia. Registro de accesos desde la app.',
                        'img' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500'
                    ],
                    [
                        'nombre' => 'Sensor Detector de Gas y Humo Smart',
                        'marca' => 'Sonoff',
                        'precio' => '140.00',
                        'descripcion' => 'Alarma sonora integrada de 85dB y notificación inmediata al teléfono celular al detectar fugas de gas LP o presencia de humo.',
                        'img' => 'https://images.unsplash.com/photo-1595435742656-5272d0b3fa82?w=500'
                    ],
                    [
                        'nombre' => 'Foco Smart LED RGB+CCT 10W',
                        'marca' => 'Xiaomi',
                        'precio' => '75.00',
                        'descripcion' => 'Control de color e intensidad por comandos de voz (Alexa/Google Assistant), programación de horarios cronometrados y bajo consumo.',
                        'img' => 'https://images.unsplash.com/photo-1521714161819-155349685541?w=500'
                    ],
                    [
                        'nombre' => 'Router Inalámbrico Gigabit Rompemuros',
                        'marca' => 'TP-Link',
                        'precio' => '295.00',
                        'descripcion' => '4 antenas externas de alta ganancia, doble banda de transmisión (2.4GHz y 5GHz) perfecta para streaming 4K y domótica estable.',
                        'img' => 'https://images.unsplash.com/photo-1557597774-9d273605dfa9?w=500'
                    ]
                ];
            @endphp

            @foreach($productosDemos as $prod)
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
                    
                    <div class="w-full h-48 bg-gray-50 overflow-hidden relative">
                        <img src="{{ $prod['img'] }}" alt="Demo Casatek" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 right-3 bg-[#22C55E] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">Demo</span>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1">
                            <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">
                                {{ $prod['marca'] }}
                            </span>
                            
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-[#22C55E] transition-colors" title="{{ $prod['nombre'] }}">
                                {{ $prod['nombre'] }}
                            </h3>
                            
                            <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                                {{ $prod['descripcion'] }}
                            </p>
                            
                            <div class="pt-1">
                                <span class="text-lg font-black text-gray-900">
                                    {{ $prod['precio'] }} Bs.
                                </span>
                            </div>
                        </div>

                        <a href="https://wa.me/59176216837" target="_blank" class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar por WhatsApp
                        </a>
                    </div>
                </div>
            @endforeach

        @endforelse
    </div>
</section>
@endsection