<header class="w-full font-['Poppins'] sticky top-0 z-[9999]">

    <p class="bg-[#1b803a] text-white text-xs md:text-sm py-2 px-4 md:px-12 flex flex-col md:flex-row justify-between items-center gap-2 font-medium tracking-wide text-center md:text-left shadow-inner">
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
        </span>
    </p>

    <nav class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-100 dark:border-gray-700 px-4 md:px-12 py-3 flex justify-between items-center">

        <a href="{{ url('/') }}" class="flex items-center gap-3 text-xl font-black tracking-tight text-[#111111] dark:text-white group">
            <img src="{{ asset('images/logoCasatek.jpg') }}" alt="Logo Casatek"
                class="h-14 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
            <span class="font-['Poppins'] tracking-tight text-4xl font-black">
                CASA<span class="text-[#22C55E]">TEK</span>
            </span>
        </a>

        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-[#22C55E] hover:bg-green-600 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-gauge-high text-sm"></i>
                Panel de Administración
            </a>
            <button onclick="abrirModal()"
                    class="flex items-center gap-2 px-5 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 hover:border-red-300 text-red-600 text-sm font-bold rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-user-shield text-sm"></i>
                Añadir nuevo admin
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-full">
                <div class="w-7 h-7 bg-[#22C55E] rounded-full flex items-center justify-center text-white text-xs font-black">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="text-sm font-bold text-gray-800 dark:text-gray-200 max-w-[120px] truncate">
                    {{ auth()->user()->name ?? 'Administrador' }}
                </span>
            </div>

            <form method="POST" action="{{ route('logout.admin') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 text-sm font-bold rounded-xl transition-all">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    <span class="hidden md:inline">Salir</span>
                </button>
            </form>

            <button id="btn-open-menu-mobile-admin" class="block md:hidden text-xl text-gray-800 dark:text-gray-200 hover:text-[#22C55E] focus:outline-none p-2">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    {{-- Sidebar overlay --}}
    <div id="sidebar-overlay-admin" class="fixed inset-0 bg-black/40 z-40 hidden opacity-0 transition-opacity duration-300"></div>

    {{-- Sidebar panel --}}
    <div id="sidebar-panel-admin" class="fixed top-0 right-0 h-full w-72 bg-[#111111] text-white z-50 p-6 flex flex-col gap-6 transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl">

        <div class="flex justify-between items-center">
            <span class="font-['Poppins'] text-xl font-black">
                CASA<span class="text-[#22C55E]">TEK</span>
                <span class="text-xs font-normal text-gray-400">Admin</span>
            </span>
            <button id="btn-close-sidebar-admin" class="text-red-500 hover:text-red-400 text-xl p-2 focus:outline-none">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="flex items-center gap-3 py-3 border-b border-gray-800">
            <div class="w-10 h-10 bg-[#22C55E] rounded-full flex items-center justify-center text-white font-black">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <p class="font-bold text-white text-sm">{{ auth()->user()->name ?? 'Administrador' }}</p>
                <p class="text-xs text-gray-400">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 text-base font-bold text-white hover:text-[#22C55E] transition-colors">
                <i class="fa-solid fa-gauge-high w-5 text-[#22C55E]"></i>
                Panel de Administración
            </a>
            <button onclick="abrirModal()"
                    class="flex items-center gap-3 text-base font-bold text-white hover:text-red-400 transition-colors">
                <i class="fa-solid fa-user-shield w-5 text-red-400"></i>
                Añadir nuevo admin
            </button>
        </div>

        <div class="mt-auto border-t border-gray-800 pt-4">
            <form method="POST" action="{{ route('logout.admin') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-base font-bold text-red-400 hover:text-red-300 transition-colors">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Cerrar sesión
                </button>
            </form>
        </div>

    </div>

</header>

{{-- ══ MODAL CREAR ADMINISTRADOR ══ --}}
<div id="modalCrearAdmin"
     onclick="cerrarModalFuera(event)"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);z-index:200;align-items:center;justify-content:center;padding:20px;">

    <div onclick="event.stopPropagation()"
         style="background:#111f16;border:1px solid rgba(239,68,68,.25);border-radius:22px;width:100%;max-width:440px;box-shadow:0 24px 60px rgba(0,0,0,.6);animation:modal-in .22s ease;">

        <style>
            @keyframes modal-in {
                from { transform:scale(.94); opacity:0; }
                to   { transform:scale(1);   opacity:1; }
            }
        </style>

        <div style="padding:18px 22px;border-bottom:1px solid rgba(239,68,68,.18);display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:16px;font-weight:800;color:#fff;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-user-shield" style="color:#f87171"></i>
                Crear Administrador
            </h3>
            <button onclick="cerrarModal()"
                    style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;color:#6b7280;cursor:pointer;font-size:15px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding:20px 22px 22px;">

            @if(session('admin_created'))
            <div style="margin:0 0 14px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;font-size:12px;padding:10px 14px;border-radius:12px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('admin_created') }}
            </div>
            @endif

            @if(session('admin_error'))
            <div style="margin:0 0 14px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:12px;padding:10px 14px;border-radius:12px;">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('admin_error') }}
            </div>
            @endif

            @if($errors->has('admin_name') || $errors->has('admin_email') || $errors->has('admin_password'))
            <div style="margin:0 0 14px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:12px;padding:10px 14px;border-radius:12px;">
                @foreach(['admin_name','admin_email','admin_password'] as $field)
                    @error($field)
                        <p><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store-admin') }}">
                @csrf

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">
                        Nombre completo
                    </label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                           placeholder="Ej: Juan Pérez"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;"
                           required>
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">
                        Correo electrónico
                    </label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                           placeholder="correo@ejemplo.com"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;"
                           required>
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">
                        Contraseña
                    </label>
                    <input type="password" name="admin_password"
                           placeholder="Mínimo 8 caracteres"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;"
                           required>
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">
                        Confirmar contraseña
                    </label>
                    <input type="password" name="admin_password_confirmation"
                           placeholder="Repite la contraseña"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;"
                           required>
                </div>

                <div style="display:flex;gap:10px;margin-top:18px;">
                    <button type="button" onclick="cerrarModal()"
                            style="flex:1;padding:10px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:12px;color:#9ca3af;font-size:13px;font-weight:700;cursor:pointer;">
                        Cancelar
                    </button>
                    <button type="submit"
                            style="flex:1;padding:10px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.35);border-radius:12px;color:#f87171;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa-solid fa-user-shield"></i> Crear Admin
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ══ SCRIPTS ══ --}}
<script>
function abrirModal() {
    const m = document.getElementById('modalCrearAdmin');
    m.style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modalCrearAdmin').style.display = 'none';
}
function cerrarModalFuera(e) {
    if (e.target === document.getElementById('modalCrearAdmin')) cerrarModal();
}

@if($errors->has('admin_name') || $errors->has('admin_email') || $errors->has('admin_password'))
    abrirModal();
@endif

document.addEventListener('DOMContentLoaded', function () {
    const openBtn  = document.getElementById('btn-open-menu-mobile-admin');
    const closeBtn = document.getElementById('btn-close-sidebar-admin');
    const overlay  = document.getElementById('sidebar-overlay-admin');
    const panel    = document.getElementById('sidebar-panel-admin');

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
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }

    if (openBtn)  openBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay)  overlay.addEventListener('click', closeSidebar);
});
</script>