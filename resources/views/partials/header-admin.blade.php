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
    border-bottom:1px solid var(--border);
    position:sticky; top:0; z-index:100;
    padding:0 28px; height:64px;
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    font-family:'DM Sans',sans-serif;
}
.ah-logo { display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0; }
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
.ah-btn-logout { display:flex; align-items:center; gap:7px; padding:8px 14px; background:transparent; border:1px solid rgba(255,255,255,.08); color:var(--muted); font-size:12px; font-weight:700; border-radius:10px; cursor:pointer; transition:all .18s; font-family:'DM Sans',sans-serif; text-decoration:none; }
.ah-btn-logout:hover { border-color:rgba(239,68,68,.4); color:#f87171; background:rgba(239,68,68,.07); }

/* ── Menú móvil ── */
.ah-hamburger { display:none; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); border-radius:10px; width:36px; height:36px; align-items:center; justify-content:center; color:var(--muted); cursor:pointer; font-size:16px; transition:all .18s; flex-shrink:0; }
.ah-hamburger:hover { color:var(--green); border-color:var(--border); }

#mobileNav {
    display:none; flex-direction:column; gap:4px;
    background:rgba(6,13,10,.98); border-bottom:1px solid var(--border);
    padding:12px 16px; font-family:'DM Sans',sans-serif;
}
#mobileNav a { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; font-size:14px; font-weight:600; color:var(--muted); text-decoration:none; border:1px solid transparent; transition:all .18s; }
#mobileNav a:hover, #mobileNav a.active { color:var(--green); background:rgba(34,197,94,.08); border-color:var(--border); }
#mobileNav .mob-divider { height:1px; background:var(--border); margin:4px 0; }

@media(max-width:900px) {
    .ah-nav { display:none; }
    .ah-hamburger { display:flex; }
    .ah-logo-badge { display:none; }
}
@media(max-width:480px) {
    #adminHeader { padding:0 14px; height:58px; }
    .ah-user-name { display:none; }
}
</style>

<header id="adminHeader">
    <a href="{{ route('admin.dashboard') }}" class="ah-logo">
        <span class="ah-logo-dot"></span>
        <span class="ah-logo-name">Casatek</span>
        <span class="ah-logo-badge">Admin</span>
    </a>

    <nav class="ah-nav">
        <a href="{{ route('admin.dashboard') }}"        class="{{ request()->routeIs('admin.dashboard')   ? 'active' : '' }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="{{ route('admin.products.index') }}"   class="{{ request()->routeIs('admin.products*')   ? 'active' : '' }}"><i class="fa-solid fa-box"></i> Productos</a>
        <a href="{{ route('admin.orders.index') }}"     class="{{ request()->routeIs('admin.orders*')     ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
        <a href="{{ route('admin.users.index') }}"      class="{{ request()->routeIs('admin.users*')      ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Usuarios</a>
    </nav>

    <div class="ah-actions">
        <div class="ah-user">
            <div class="ah-avatar"><i class="fa-solid fa-circle-user"></i></div>
            <span class="ah-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
        <form method="POST" action="{{ route('logout.admin') }}">
            @csrf
            <button type="submit" class="ah-btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="hidden-xs">Salir</span>
            </button>
        </form>
        <button class="ah-hamburger" onclick="toggleMobileNav()" aria-label="Menú">
            <i class="fa-solid fa-bars" id="hambIcon"></i>
        </button>
    </div>
</header>

<nav id="mobileNav">
    <a href="{{ route('admin.dashboard') }}"        class="{{ request()->routeIs('admin.dashboard')   ? 'active' : '' }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
    <a href="{{ route('admin.products.index') }}"   class="{{ request()->routeIs('admin.products*')   ? 'active' : '' }}"><i class="fa-solid fa-box"></i> Productos</a>
    <a href="{{ route('admin.orders.index') }}"     class="{{ request()->routeIs('admin.orders*')     ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
    <a href="{{ route('admin.users.index') }}"      class="{{ request()->routeIs('admin.users*')      ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Usuarios</a>
</nav>

<script>
function toggleMobileNav() {
    const nav  = document.getElementById('mobileNav');
    const icon = document.getElementById('hambIcon');
    const open = nav.style.display === 'flex';
    nav.style.display = open ? 'none' : 'flex';
    icon.className    = open ? 'fa-solid fa-bars' : 'fa-solid fa-xmark';
}
</script>