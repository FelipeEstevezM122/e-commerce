<header class="w-full font-['Poppins'] relative">

    <p
        class="bg-[#1b803a] text-white text-xs md:text-sm py-2 px-4 md:px-12 flex flex-col md:flex-row justify-between items-center gap-2 font-medium tracking-wide text-center md:text-left shadow-inner">
        <span>
            Ordene antes de las 16:30 pm, reciba su pedido hoy -
            <strong class="font-bold text-yellow-300">PEDIDOS AL: 76216837 - 77297541</strong>
        </span>

        <span class="flex items-center gap-6 text-white text-sm">
            <span class="flex items-center gap-4">
                <a href="#" class="hover:text-gray-200 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="hover:text-gray-200 transition-colors"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" class="hover:text-gray-200 transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
            </span>
            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-200 transition-colors">
                <span>ESP</span>
                <i class="fa-solid fa-chevron-down text-[10px]"></i>
            </span>
        </span>
    </p>

    <nav
        class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-100 dark:border-gray-700 px-4 md:px-12 py-3 flex justify-between items-center">
        <a href="{{ url('/') }}"
            class="flex items-center gap-3 text-xl font-black tracking-tight text-[#111111] dark:text-white group">
            <img src="{{ asset('images/logoCasatek.jpg') }}" alt="Logo Casatek"
                class="h-14 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
            <span class="font-['Poppins'] tracking-tight text-4xl font-black">
                CASA<span class="text-[#22C55E]">TEK</span>
            </span>
        </a>

        <div class="flex items-center gap-4 md:gap-8">
            <ul
                class="hidden md:flex items-center gap-8 text-sm font-bold tracking-wide text-[#111111] dark:text-gray-200">
                <li class="relative group"><a href="{{ url('/') }}"
                        class="hover:text-[#22C55E] transition-colors {{ Request::is('/') ? 'text-[#22C55E]' : '' }}">INICIO</a><span
                        class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 {{ Request::is('/') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </li>
                <li class="relative group"><a href="{{ url('/productos') }}"
                        class="hover:text-[#22C55E] transition-colors {{ Request::is('productos') ? 'text-[#22C55E]' : '' }}">PRODUCTOS</a><span
                        class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 {{ Request::is('productos') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </li>
                <li class="relative group"><a href="{{ url('/nosotros') }}"
                        class="hover:text-[#22C55E] transition-colors {{ Request::is('nosotros') ? 'text-[#22C55E]' : '' }}">NOSOTROS</a><span
                        class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 {{ Request::is('nosotros') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </li>
                <li class="relative group"><a href="{{ url('/contactanos') }}"
                        class="hover:text-[#22C55E] transition-colors {{ Request::is('contactanos') ? 'text-[#22C55E]' : '' }}">CONTÁCTANOS</a><span
                        class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 {{ Request::is('contactanos') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </li>
                <li class="relative group"><a href="{{ url('/iniciarsesion') }}"
                        class="hover:text-[#22C55E] transition-colors {{ Request::is('iniciarsesion') ? 'text-[#22C55E]' : '' }}">INICIAR
                        SESION</a><span
                        class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 {{ Request::is('iniciarsesion') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </li>
                <li class="relative group"><a href="{{ url('/carrito') }}"
                        class="text-lg hover:text-[#22C55E] transition-colors"><i
                            class="fa-solid fa-cart-shopping"></i></a></li>
                <li class="relative group"><button id="theme-toggle"
                        class="text-lg hover:text-[#22C55E] transition-colors"><i id="theme-icon"
                            class="fa-solid fa-moon"></i></button></li>
            </ul>

            <button id="btn-open-sidebar"
                class="hidden md:block text-xl text-[#22C55E] hover:text-green-700 focus:outline-none p-2 group"><i
                    class="fa-solid fa-clock-rotate-left transform group-hover:-translate-y-0.5 transition-transform duration-200"></i></button>
            <button id="btn-open-menu-mobile"
                class="block md:hidden text-xl text-gray-800 dark:text-gray-200 hover:text-[#22C55E] focus:outline-none p-2"><i
                    class="fa-solid fa-bars"></i></button>
        </div>
    </nav>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden opacity-0 transition-opacity duration-300">
    </div>
    <div id="sidebar-panel"
        class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-[#111111] text-white z-50 p-8 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-y-auto select-none">
        <div>
            <div class="flex justify-end">
                <button id="btn-close-sidebar"
                    class="text-red-500 hover:text-red-400 text-xl font-bold transition-colors p-2 focus:outline-none"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="mt-4 flex justify-center">
                <h2 class="text-3xl font-black tracking-widest text-white font-['Poppins']">CASA<span
                        class="text-white/90 font-light">TEK</span></h2>
            </div>
            <div class="block md:hidden mt-8 space-y-4 border-b border-gray-800/60 pb-6">
                <h3 class="text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Navegación</h3>
                <a href="{{ url('/') }}"
                    class="block text-lg font-bold {{ Request::is('/') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Inicio</a>
                <a href="{{ url('/productos') }}"
                    class="block text-lg font-bold {{ Request::is('productos') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Productos</a>
                <a href="{{ url('/nosotros') }}"
                    class="block text-lg font-bold {{ Request::is('nosotros') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Nosotros</a>
                <a href="{{ url('/iniciarsesion') }}"
                    class="block text-lg font-bold {{ Request::is('iniciarsesion') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Iniciar
                    Sesión</a>
                <a href="{{ url('/carrito') }}"
                    class="block text-lg font-bold {{ Request::is('carrito') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Carrito</a>
                <a href="{{ url('/contactanos') }}"
                    class="block text-lg font-bold {{ Request::is('contactanos') ? 'text-[#22C55E]' : 'text-white' }} hover:text-[#22C55E] transition-colors">Contáctanos</a>
                <div class="pt-3">
                    <button id="theme-toggle-mobile"
                        class="flex items-center gap-3 text-lg font-bold text-white hover:text-[#22C55E] transition-colors">
                        <i id="theme-icon-mobile" class="fa-solid fa-moon"></i>
                        <span>Modo Oscuro</span>
                    </button>
                </div>
            </div>
            <div class="mt-8 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="text-[#22C55E] text-2xl animate-pulse"><i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="text-xl font-extrabold tracking-tight">Horarios de trabajo</h3>
                </div>
                <div class="space-y-1 font-medium pl-9">
                    <p class="text-xs text-gray-400">Lunes a Viernes</p>
                    <p class="text-sm text-gray-200">9:00 - 13:00 y de 14:30 - 19:00</p>
                </div>
                <div class="space-y-1 font-medium pt-2 pl-9">
                    <p class="text-xs text-gray-400">Sábados</p>
                    <p class="text-sm text-gray-200">9:00 - 13:00</p>
                </div>
            </div>
            <div class="mt-8 space-y-4">
                <h3 class="text-xl font-extrabold tracking-tight pl-9 md:pl-0">Contactos</h3>
                <div class="space-y-1 font-medium pl-9">
                    <p class="text-xs text-gray-400">Linea de Soporte:</p>
                    <p class="text-sm text-gray-200">76572358</p>
                </div>
                <div class="space-y-1 font-medium pt-2 pl-9">
                    <p class="text-xs text-gray-400">Dirección:</p>
                    <p class="text-sm text-gray-200 leading-relaxed">Calle Colombia Nro. 218<br>Zona San Pedro, La Paz -
                        Bolivia</p>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-800/60 flex items-center justify-between">
            <div class="flex items-center gap-2"><span class="w-8 h-[1px] bg-gray-500"></span><span
                    class="text-[11px] font-bold text-gray-400 tracking-wider uppercase">Siguenos</span></div>
            <div class="flex items-center gap-3">
                <a href="#"
                    class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center text-sm hover:bg-gray-200 transition-colors shadow-sm"><i
                        class="fa-brands fa-facebook-f"></i></a>
                <a href="#"
                    class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center text-sm hover:bg-gray-200 transition-colors shadow-sm"><i
                        class="fa-brands fa-youtube"></i></a>
                <a href="#"
                    class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center text-sm hover:bg-gray-200 transition-colors shadow-sm"><i
                        class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openSidebarBtn = document.getElementById('btn-open-sidebar');
        const openMenuMobileBtn = document.getElementById('btn-open-menu-mobile');
        const closeBtn = document.getElementById('btn-close-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const panel = document.getElementById('sidebar-panel');
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const themeToggleMobile = document.getElementById('theme-toggle-mobile');
        const themeIconMobile = document.getElementById('theme-icon-mobile');

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');

            if (themeIcon) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }

            if (themeIconMobile) {
                themeIconMobile.classList.replace('fa-moon', 'fa-sun');
            }
        }

        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        });
        if (themeToggleMobile) {
            themeToggleMobile.addEventListener('click', () => {

                document.documentElement.classList.toggle('dark');

                if (document.documentElement.classList.contains('dark')) {

                    localStorage.setItem('theme', 'dark');

                    if (themeIcon) {
                        themeIcon.classList.replace('fa-moon', 'fa-sun');
                    }

                    if (themeIconMobile) {
                        themeIconMobile.classList.replace('fa-moon', 'fa-sun');
                    }

                } else {

                    localStorage.setItem('theme', 'light');

                    if (themeIcon) {
                        themeIcon.classList.replace('fa-sun', 'fa-moon');
                    }

                    if (themeIconMobile) {
                        themeIconMobile.classList.replace('fa-sun', 'fa-moon');
                    }
                }
            });
        }

        function openSidebar() {
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.add('opacity-100');
                panel.classList.remove('translate-x-full');
            }, 20);
        }

        function closeSidebar() {
            overlay.classList.remove('opacity-100');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
        if (openMenuMobileBtn) openMenuMobileBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    });
</script>