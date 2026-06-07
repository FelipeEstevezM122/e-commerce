<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
#adminHeader {
    background:rgba(6,13,10,.95); backdrop-filter:blur(16px);
    border-bottom:1px solid var(--border); position:sticky; top:0; z-index:100;
    padding:0 28px; height:64px; display:flex; align-items:center; justify-content:space-between; gap:16px;
    font-family:'DM Sans',sans-serif;
}
.ah-logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
.ah-logo-dot { width:9px; height:9px; background:var(--green); border-radius:50%; box-shadow:0 0 8px rgba(34,197,94,.6); animation:pulse-dot 2.5s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{box-shadow:0 0 6px rgba(34,197,94,.5)} 50%{box-shadow:0 0 14px rgba(34,197,94,.9)} }
.ah-logo-name { font-family:'Syne',sans-serif; font-size:19px; font-weight:800; color:#fff; }
.ah-logo-badge { background:rgba(34,197,94,.15); border:1px solid var(--border-h); color:var(--green); font-size:9px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; padding:3px 10px; border-radius:20px; }
.ah-nav { display:flex; align-items:center; gap:2px; flex:1; justify-content:center; }
.ah-nav a { display:flex; align-items:center; gap:7px; padding:7px 14px; border-radius:10px; font-size:13px; font-weight:600; color:var(--muted); text-decoration:none; border:1px solid transparent; transition:all .18s; white-space:nowrap; }
.ah-nav a:hover, .ah-nav a.active { color:var(--green); background:rgba(34,197,94,.08); border-color:var(--border); }
.ah-actions { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.ah-user { display:flex; align-items:center; gap:8px; padding:5px 12px 5px 5px; background:rgba(34,197,94,.07); border:1px solid var(--border); border-radius:30px; }
.ah-avatar { width:32px; height:32px; border-radius:50%; background:rgba(34,197,94,.18); border:1px solid var(--border-h); display:flex; align-items:center; justify-content:center; color:var(--green); font-size:13px; }
.ah-user-name { font-size:12px; font-weight:700; color:#d1fae5; }
.ah-btn-logout { display:flex; align-items:center; gap:7px; padding:8px 14px; background:transparent; border:1px solid rgba(255,255,255,.08); color:var(--muted); font-size:12px; font-weight:700; border-radius:10px; cursor:pointer; transition:all .18s; font-family:'DM Sans',sans-serif; }
.ah-btn-logout:hover { border-color:rgba(239,68,68,.4); color:#f87171; background:rgba(239,68,68,.07); }
.ah-btn-newadmin { display:flex; align-items:center; gap:7px; padding:8px 14px; background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); color:#f87171; font-size:12px; font-weight:700; border-radius:10px; cursor:pointer; transition:all .18s; font-family:'DM Sans',sans-serif; }
.ah-btn-newadmin:hover { background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.4); }
@media(max-width:768px) { #adminHeader { padding:0 16px; } .ah-nav { display:none; } }
</style>

<header id="adminHeader">
    <a href="{{ route('admin.dashboard') }}" class="ah-logo">
        <span class="ah-logo-dot"></span>
        <span class="ah-logo-name">Casatek</span>
        <span class="ah-logo-badge">Admin</span>
    </a>

    <nav class="ah-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i> Productos
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i> Pedidos
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Usuarios
        </a>
        @if(Route::has('admin.tickets.index'))
        <a href="{{ route('admin.tickets.index') }}"
           class="{{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket"></i> Tickets
        </a>
        @endif
    </nav>

    <div class="ah-actions">
        <button onclick="abrirModal()" class="ah-btn-newadmin">
            <i class="fa-solid fa-user-shield"></i>
            <span class="hidden md:inline">Nuevo Admin</span>
        </button>
        <div class="ah-user">
            <div class="ah-avatar"><i class="fa-solid fa-circle-user"></i></div>
            <span class="ah-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
        <form method="POST" action="{{ route('logout.admin') }}">
            @csrf
            <button type="submit" class="ah-btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="hidden md:inline">Salir</span>
            </button>
        </form>
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
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">Nombre completo</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                           placeholder="Ej: Juan Pérez"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;" required>
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">Correo electrónico</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                           placeholder="correo@ejemplo.com"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;" required>
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">Contraseña</label>
                    <input type="password" name="admin_password"
                           placeholder="Mínimo 8 caracteres"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;" required>
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;margin-bottom:6px;">Confirmar contraseña</label>
                    <input type="password" name="admin_password_confirmation"
                           placeholder="Repite la contraseña"
                           style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;font-size:13px;color:#fff;outline:none;" required>
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
</script>