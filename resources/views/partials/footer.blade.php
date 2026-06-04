<footer class="bg-[#111111] text-white pt-16 pb-8 border-t-4 border-[#22C55E]">
    <div class="max-w-7xl mx-auto px-4 md:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
        
        <div class="space-y-4">
            <a href="#" class="text-2xl font-black tracking-tight flex items-center gap-2">
                CASA<span class="text-[#22C55E]">TEK</span>
            </a>
            <p class="text-xs text-gray-400 leading-relaxed">
                Expertos en soluciones de seguridad electrónica, automatización y redes. Comprometidos con la protección y eficiencia de tu hogar y empresa.
            </p>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-bold tracking-widest uppercase text-[#22C55E]">Navegación</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Inicio</a></li>
                <li><a href="{{ url('/productos') }}" class="hover:text-white transition-colors">Catálogo de Productos</a></li>
                <li><a href="{{ url('/nosotros') }}" class="hover:text-white transition-colors">Sobre Nosotros</a></li>
                <li><a href="{{ url('/contactanos') }}" class="hover:text-white transition-colors">Contáctanos</a></li>
            </ul>
        </div>

        <div class="space-y-4 text-sm">
            <h4 class="text-sm font-bold tracking-widest uppercase text-[#22C55E]">Soporte</h4>
            <ul class="space-y-3 text-gray-400">
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-location-dot mt-1"></i>
                    <span>Calle Colombia Nro. 218, Zona San Pedro, La Paz - Bolivia</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-phone"></i>
                    <span>76216837 - 77297541</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope"></i>
                    <span>casatekbolivia@gmail.com</span>
                </li>
            </ul>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-bold tracking-widest uppercase text-[#22C55E]">Síguenos</h4>
            <p class="text-xs text-gray-400">Mantente al tanto de las últimas novedades en tecnología.</p>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#22C55E] transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#22C55E] transition-all"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#22C55E] transition-all"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-12 mt-12 pt-8 border-t border-gray-800 text-center text-xs text-gray-500">
        <p>&copy; {{ date('Y') }} Casatek Bolivia. Todos los derechos reservados.</p>
    </div>
</footer>