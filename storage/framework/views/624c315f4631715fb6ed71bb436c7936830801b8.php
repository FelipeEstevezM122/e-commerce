<header class="w-full font-['Poppins'] sticky top-0 z-[9999]">

    <nav class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-100 dark:border-gray-700 px-4 md:px-12 py-3 flex justify-between items-center">
        <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-3 text-xl font-black tracking-tight text-[#111111] dark:text-white group">
            <img src="<?php echo e(asset('images/logoCasatek.png')); ?>" alt="Logo Casatek"
                class="h-14 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
            <span class="font-['Poppins'] tracking-tight text-4xl font-black">
                CASA<span class="text-[#22C55E]">TEK</span>
            </span>
        </a>

        <div class="flex items-center gap-4 md:gap-8">
            <ul class="hidden md:flex items-center gap-8 text-sm font-bold tracking-wide text-[#111111] dark:text-gray-200">
                <li class="relative group">
                    <a href="<?php echo e(url('/')); ?>" class="hover:text-[#22C55E] transition-colors <?php echo e(Request::is('/') ? 'text-[#22C55E]' : ''); ?>">INICIO</a>
                    <span class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 <?php echo e(Request::is('/') ? 'w-full' : 'w-0 group-hover:w-full'); ?>"></span>
                </li>
                <li class="relative group">
                    <a href="<?php echo e(url('/productos')); ?>" class="hover:text-[#22C55E] transition-colors <?php echo e(Request::is('productos') ? 'text-[#22C55E]' : ''); ?>">PRODUCTOS</a>
                    <span class="absolute left-0 -bottom-[22px] h-[3px] bg-[#22C55E] rounded-t-full transition-all duration-300 <?php echo e(Request::is('productos') ? 'w-full' : 'w-0 group-hover:w-full'); ?>"></span>
                </li>


                 <!-- INICIAR SESIÓN / PERFIL  -->
                <li class="relative" id="nav-auth-desktop">
                    <a id="btn-login-desktop" href="<?php echo e(url('/iniciarsesion')); ?>"
                       class="hover:text-[#22C55E] transition-colors <?php echo e(Request::is('iniciarsesion') ? 'text-[#22C55E]' : ''); ?>">
                        INICIAR SESIÓN
                    </a>

                   <!--  Dropdown perfil  -->
                    <div id="btn-perfil-desktop" class="hidden relative group/perfil">
                        <button class="flex items-center gap-2 hover:text-[#22C55E] transition-colors focus:outline-none">
                            <div class="w-8 h-8 bg-[#22C55E] rounded-full flex items-center justify-center text-white text-sm font-black" id="avatar-inicial">U</div>
                            <span id="nav-username" class="max-w-[100px] truncate text-sm">Usuario</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>

                        
                        <div class="absolute right-0 top-[calc(100%+12px)] w-64 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl z-[9999] py-2 invisible opacity-0 group-hover/perfil:visible group-hover/perfil:opacity-100 transition-all duration-200">

                          
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="font-black text-gray-900 dark:text-white text-sm truncate" id="dd-nombre">-</p>
                                <p class="text-xs text-gray-400 truncate" id="dd-email">-</p>
                                <span id="dd-rango" class="mt-1 inline-block text-[10px] font-bold bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full"></span>
                            </div>

                            <a href="<?php echo e(url('/carrito')); ?>"
                               class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors font-semibold">
                                <i class="fa-solid fa-cart-shopping w-4"></i> Mi carrito
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                                <button onclick="cerrarSesion()"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-semibold">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i> Cerrar sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <button id="btn-open-menu-mobile" class="block md:hidden text-xl text-gray-800 dark:text-gray-200 hover:text-[#22C55E] focus:outline-none p-2">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>


    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden opacity-0 transition-opacity duration-300"></div>
    <div id="sidebar-panel" class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-[#111111] text-white z-50 p-8 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-y-auto select-none">
        <div>
            <div class="flex justify-end">
                <button id="btn-close-sidebar" class="text-red-500 hover:text-red-400 text-xl font-bold transition-colors p-2 focus:outline-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="mt-4 flex justify-center">
                <h2 class="text-3xl font-black tracking-widest text-white font-['Poppins']">CASA<span class="text-white/90 font-light">TEK</span></h2>
            </div>

            
            <div class="block md:hidden mt-8 space-y-4 border-b border-gray-800/60 pb-6">
                <h3 class="text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Navegación</h3>
                <a href="<?php echo e(url('/')); ?>" class="block text-lg font-bold <?php echo e(Request::is('/') ? 'text-[#22C55E]' : 'text-white'); ?> hover:text-[#22C55E] transition-colors">Inicio</a>
                <a href="<?php echo e(url('/productos')); ?>" class="block text-lg font-bold <?php echo e(Request::is('productos') ? 'text-[#22C55E]' : 'text-white'); ?> hover:text-[#22C55E] transition-colors">Productos</a>
                <a href="<?php echo e(url('/nosotros')); ?>" class="block text-lg font-bold <?php echo e(Request::is('nosotros') ? 'text-[#22C55E]' : 'text-white'); ?> hover:text-[#22C55E] transition-colors">Nosotros</a>
                <a href="<?php echo e(url('/contactanos')); ?>" class="block text-lg font-bold <?php echo e(Request::is('contactanos') ? 'text-[#22C55E]' : 'text-white'); ?> hover:text-[#22C55E] transition-colors">Contáctanos</a>
                <a href="<?php echo e(url('/carrito')); ?>" class="block text-lg font-bold <?php echo e(Request::is('carrito') ? 'text-[#22C55E]' : 'text-white'); ?> hover:text-[#22C55E] transition-colors">Carrito</a>

                <!-- Manuales mobile -->
                <div class="space-y-2 pt-1">
                    <h3 class="text-xs font-bold text-gray-500 tracking-widest uppercase">Manuales</h3>
                    <a href="<?php echo e(asset('manuales/Manual técnico.pdf')); ?>" download
                       class="flex items-center gap-2 text-base font-bold text-white hover:text-[#22C55E] transition-colors">
                        <i class="fa-solid fa-file-pdf text-red-400"></i> Manual Técnico
                    </a>
                    <a href="<?php echo e(asset('manuales/Manual de usuario.pdf')); ?>" download
                       class="flex items-center gap-2 text-base font-bold text-white hover:text-[#22C55E] transition-colors">
                        <i class="fa-solid fa-file-pdf text-red-400"></i> Manual de Usuario
                    </a>
                </div>

                <div id="mobile-login">
                    <a href="<?php echo e(url('/iniciarsesion')); ?>" class="block text-lg font-bold text-white hover:text-[#22C55E] transition-colors">Iniciar Sesión</a>
                </div>
                <div id="mobile-perfil" class="hidden space-y-3">
                    <div class="flex items-center gap-3 py-2">
                        <div class="w-10 h-10 bg-[#22C55E] rounded-full flex items-center justify-center font-black text-white" id="mobile-avatar">U</div>
                        <div>
                            <p class="font-bold text-white text-sm" id="mobile-nombre">-</p>
                            <p class="text-xs text-gray-400" id="mobile-email">-</p>
                        </div>
                    </div>
                    <button onclick="abrirModalPerfil(); closeSidebarFn();" class="block text-lg font-bold text-white hover:text-[#22C55E] transition-colors">Editar perfil</button>
                    <button onclick="cerrarSesion()" class="block text-lg font-bold text-red-400 hover:text-red-300 transition-colors">Cerrar sesión</button>
                </div>

            </div>

</header>

<div id="modalPerfil"
     class="fixed inset-0 bg-black/50 z-[99999] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-200" id="modalPerfilBox">

        
        <div class="bg-gradient-to-r from-[#111111] via-[#1b803a] to-[#22C55E] p-6 text-white relative">
            <button onclick="cerrarModalPerfil()" class="absolute top-4 right-4 bg-black/30 hover:bg-black/50 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-black" id="perfil-avatar-grande">U</div>
                <div>
                    <p class="font-black text-xl" id="perfil-nombre-header">-</p>
                    <p class="text-white/70 text-sm" id="perfil-email-header">-</p>
                    <span id="perfil-rango-header" class="mt-1 inline-block text-[10px] font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-400/30 px-2 py-0.5 rounded-full"></span>
                </div>
            </div>
        </div>

        
        <div class="p-6 space-y-4 overflow-y-auto max-h-[60vh]">

            <div id="perfil-error"  class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl"></div>
            <div id="perfil-exito"  class="hidden bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> <span>Datos actualizados correctamente</span>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre completo</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                    <input type="text" id="perfil-input-nombre" placeholder="Tu nombre"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                    <input type="email" id="perfil-input-email" placeholder="correo@ejemplo.com"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Teléfono <span class="text-gray-400 font-normal normal-case">(opcional)</span></label>
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                    <input type="tel" id="perfil-input-phone" placeholder="591XXXXXXXX"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">WhatsApp <span class="text-gray-400 font-normal normal-case">(opcional)</span></label>
                <div class="relative">
                    <i class="fa-brands fa-whatsapp absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                    <input type="tel" id="perfil-input-whatsapp" placeholder="591XXXXXXXX"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                </div>
            </div>

            
            <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Cambiar contraseña <span class="font-normal normal-case">(dejar vacío para no cambiar)</span></p>

                <div class="space-y-3">
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                        <input type="password" id="perfil-input-password" placeholder="Nueva contraseña"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[#22C55E] text-sm"></i>
                        <input type="password" id="perfil-input-password-confirm" placeholder="Confirmar nueva contraseña"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-[#22C55E] focus:outline-none">
                    </div>
                </div>
            </div>

        </div>

        
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-3">
            <button onclick="cerrarModalPerfil()" class="flex-1 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-bold py-2.5 rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Cancelar
            </button>
            <button onclick="guardarPerfil()" id="btn-guardar-perfil"
                    class="flex-1 bg-[#22C55E] hover:bg-green-600 text-white font-bold py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
            </button>
        </div>
    </div>
</div>

<script>
    // ── Cerrar sidebar
    function closeSidebarFn() {
        const overlay = document.getElementById('sidebar-overlay');
        const panel   = document.getElementById('sidebar-panel');
        overlay.classList.remove('opacity-100');
        panel.classList.add('translate-x-full');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }

    // ── Inicializar navbar según sesión ──
    function inicializarNavAuth() {
        const token = localStorage.getItem('token');
        const user  = JSON.parse(localStorage.getItem('user') || 'null');
        const rank  = JSON.parse(localStorage.getItem('rank') || 'null');

        if (token && user) {
            // Desktop
            document.getElementById('btn-login-desktop').classList.add('hidden');
            document.getElementById('btn-perfil-desktop').classList.remove('hidden');

            const inicial = (user.name || 'U')[0].toUpperCase();
            document.getElementById('avatar-inicial').textContent    = inicial;
            document.getElementById('nav-username').textContent      = user.name?.split(' ')[0] ?? 'Usuario';
            document.getElementById('dd-nombre').textContent         = user.name  ?? '-';
            document.getElementById('dd-email').textContent          = user.email ?? '-';
            document.getElementById('dd-rango').textContent          = rank?.name ? '🏅 ' + rank.name : '';

            // Mobile
            document.getElementById('mobile-login').classList.add('hidden');
            document.getElementById('mobile-perfil').classList.remove('hidden');
            document.getElementById('mobile-avatar').textContent = inicial;
            document.getElementById('mobile-nombre').textContent = user.name  ?? '-';
            document.getElementById('mobile-email').textContent  = user.email ?? '-';
        }
    }

    // ── Abrir modal perfil ──
    function abrirModalPerfil() {
        const user = JSON.parse(localStorage.getItem('user') || 'null');
        const rank = JSON.parse(localStorage.getItem('rank') || 'null');
        if (!user) return;

        const inicial = (user.name || 'U')[0].toUpperCase();
        document.getElementById('perfil-avatar-grande').textContent  = inicial;
        document.getElementById('perfil-nombre-header').textContent  = user.name  ?? '-';
        document.getElementById('perfil-email-header').textContent   = user.email ?? '-';
        document.getElementById('perfil-rango-header').textContent   = rank?.name ? '🏅 ' + rank.name : '';

        document.getElementById('perfil-input-nombre').value    = user.name     ?? '';
        document.getElementById('perfil-input-email').value     = user.email    ?? '';
        document.getElementById('perfil-input-phone').value     = user.phone    ?? '';
        document.getElementById('perfil-input-whatsapp').value  = user.whatsapp ?? '';
        document.getElementById('perfil-input-password').value         = '';
        document.getElementById('perfil-input-password-confirm').value = '';

        document.getElementById('perfil-error').classList.add('hidden');
        document.getElementById('perfil-exito').classList.add('hidden');

        const modal = document.getElementById('modalPerfil');
        const box   = document.getElementById('modalPerfilBox');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }

    function cerrarModalPerfil() {
        const modal = document.getElementById('modalPerfil');
        const box   = document.getElementById('modalPerfilBox');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
    }

    //  Guardar perfil
    async function guardarPerfil() {
        const token = localStorage.getItem('token');
        if (!token) return;

        const btn       = document.getElementById('btn-guardar-perfil');
        const errorDiv  = document.getElementById('perfil-error');
        const exitoDiv  = document.getElementById('perfil-exito');
        const password  = document.getElementById('perfil-input-password').value;
        const passConf  = document.getElementById('perfil-input-password-confirm').value;

        errorDiv.classList.add('hidden');
        exitoDiv.classList.add('hidden');

        if (password && password !== passConf) {
            errorDiv.textContent = 'Las contraseñas no coinciden.';
            errorDiv.classList.remove('hidden');
            return;
        }

        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
        btn.disabled  = true;

        const body = {
            name:     document.getElementById('perfil-input-nombre').value.trim(),
            email:    document.getElementById('perfil-input-email').value.trim(),
            phone:    document.getElementById('perfil-input-phone').value.trim()    || null,
            whatsapp: document.getElementById('perfil-input-whatsapp').value.trim() || null,
        };
        if (password) {
            body.password              = password;
            body.password_confirmation = passConf;
        }

        try {
            const res  = await fetch('/api/me/update', {
                method: 'PUT',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'Authorization': 'Bearer ' + token,
                },
                body: JSON.stringify(body),
            });

            const data = await res.json();

            if (!res.ok) {
                const msgs = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data.message || 'Error al actualizar.');
                errorDiv.textContent = msgs;
                errorDiv.classList.remove('hidden');
                return;
            }

            // Actualizar localStorage con los nuevos datos
            localStorage.setItem('user', JSON.stringify(data.user));
            inicializarNavAuth();

            exitoDiv.classList.remove('hidden');
            setTimeout(cerrarModalPerfil, 1500);

        } catch(e) {
            errorDiv.textContent = 'Error de conexión.';
            errorDiv.classList.remove('hidden');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar cambios';
            btn.disabled  = false;
        }
    }

    // ── Cerrar sesión ──
    async function cerrarSesion() {
        const token = localStorage.getItem('token');
        if (token) {
            try {
                await fetch('/api/logout', {
                    method:  'POST',
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
                });
            } catch(e) {}
        }
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('roles');
        localStorage.removeItem('rank');
        localStorage.removeItem('casatek_carrito');
        window.location.href = '/';
    }

    // ── Cerrar modal al click fuera ──
    document.getElementById('modalPerfil').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalPerfil();
    });


        function openSidebar() {
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.add('opacity-100');
                panel.classList.remove('translate-x-full');
            }, 20);
        }

        if (openSidebarBtn)    openSidebarBtn.addEventListener('click', openSidebar);
        if (openMenuMobileBtn) openMenuMobileBtn.addEventListener('click', openSidebar);
        if (closeBtn)          closeBtn.addEventListener('click', closeSidebarFn);
        if (overlay)           overlay.addEventListener('click', closeSidebarFn);
    });
</script><?php /**PATH C:\Users\Joker\Desktop\CASATE_v1\CASATEK\resources\views/partials/header.blade.php ENDPATH**/ ?>