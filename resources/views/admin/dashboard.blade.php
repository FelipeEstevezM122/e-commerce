@extends('layouts.admin')

@section('titulo', 'Dashboard Admin - Casatek')

@section('contenido')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700&display=swap');

:root {
    --green:      #22C55E;
    --green-dark: #15803d;
    --bg:         #060d0a;
    --surface:    #0e1a12;
    --card:       #111f16;
    --border:     rgba(34,197,94,.12);
    --border-h:   rgba(34,197,94,.35);
    --text:       #f3f4f6;
    --muted:      #6b7280;
}

#adminDash * { box-sizing: border-box; }
#adminDash { font-family: 'DM Sans', sans-serif; background: var(--bg); min-height: 100vh; color: var(--text); }

#adminMain {
    padding: 32px 28px 48px;
    max-width: 1400px;
    margin: 0 auto;
}

.section-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--green);
    margin-bottom: 6px;
}
.section-title {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin: 28px 0;
}
.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px 20px;
    transition: border-color .2s, transform .2s;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--accent-color, var(--green));
    opacity: .7;
}
.stat-card:hover {
    border-color: var(--border-h);
    transform: translateY(-2px);
}
.stat-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
}
.stat-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--muted);
}
.stat-icon {
    width: 38px; height: 38px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
}
.stat-value {
    font-family: 'Syne', sans-serif;
    font-size: 36px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 8px;
}
.stat-value.green { color: var(--green); }
.stat-sub {
    font-size: 11px;
    color: var(--muted);
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.stat-sub b { font-weight: 700; }

.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.panel {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
}
.panel-head {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.panel-head h2 {
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.panel-body { padding: 16px; }

.top-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    transition: background .15s;
    margin-bottom: 4px;
}
.top-item:hover { background: rgba(255,255,255,.03); }
.top-rank {
    width: 30px; height: 30px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
}
.top-name { flex: 1; font-size: 13px; font-weight: 600; color: #e5e7eb; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.top-badge {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.25);
    color: var(--green);
    font-size: 10px; font-weight: 800;
    padding: 3px 10px; border-radius: 20px;
    white-space: nowrap;
}

.bar-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.bar-month { font-size: 11px; color: var(--muted); width: 30px; text-align: right; flex-shrink: 0; }
.bar-track {
    flex: 1;
    height: 24px;
    background: rgba(255,255,255,.04);
    border-radius: 8px;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green-dark), var(--green));
    border-radius: 8px;
    min-width: 4px;
    display: flex; align-items: center; justify-content: flex-end; padding-right: 8px;
}
.bar-amount { font-size: 11px; font-weight: 700; color: #fff; white-space: nowrap; }

.quick-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-top: 20px;
}
.quick-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px 12px;
    text-align: center;
    text-decoration: none;
    transition: border-color .18s, transform .18s;
    cursor: pointer;
    display: block;
    font-family: 'DM Sans', sans-serif;
    background: none;
    width: 100%;
}
.quick-card:hover {
    border-color: var(--qc-color, var(--border-h));
    transform: translateY(-3px);
}
.quick-icon { font-size: 22px; margin-bottom: 8px; display: block; }
.quick-label { font-size: 11px; font-weight: 700; color: #9ca3af; }
.quick-card:hover .quick-label { color: #fff; }

.fade-up { animation: fadeUp .4s ease both; }
@keyframes fadeUp {
    from { opacity:0; transform: translateY(16px); }
    to   { opacity:1; transform: translateY(0); }
}
.delay-1 { animation-delay: .07s; }
.delay-2 { animation-delay: .14s; }
.delay-3 { animation-delay: .21s; }
.delay-4 { animation-delay: .28s; }

@media(max-width: 1100px) {
    .stat-grid   { grid-template-columns: repeat(2,1fr); }
    .quick-grid  { grid-template-columns: repeat(3,1fr); }
}
@media(max-width: 768px) {
    #adminMain   { padding: 20px 16px 40px; }
    .stat-grid   { grid-template-columns: 1fr 1fr; }
    .bottom-grid { grid-template-columns: 1fr; }
    .quick-grid  { grid-template-columns: repeat(2,1fr); }
}
@media(max-width: 480px) {
    .stat-grid  { grid-template-columns: 1fr; }
    .quick-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<div id="adminDash">

    @include('partials.header-admin')

    <main id="adminMain">

        <div class="fade-up">
            <p class="section-label"><i class="fa-solid fa-gauge-high mr-1"></i> Vista general</p>
            <h1 class="section-title">
                Panel <span style="color:var(--green)">Administrativo</span>
            </h1>
        </div>

        <div class="stat-grid">

            <div class="stat-card fade-up delay-1" style="--accent-color:#3b82f6">
                <div class="stat-top">
                    <span class="stat-label">Usuarios</span>
                    <div class="stat-icon" style="background:rgba(59,130,246,.15)">
                        <i class="fa-solid fa-users" style="color:#60a5fa"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalUsers }}</p>
                <div class="stat-sub">
                    <span><b style="color:#22C55E">{{ $finalCustomers }}</b> clientes</span>
                    <span><b style="color:#facc15">{{ $wholesalers }}</b> mayoristas</span>
                </div>
            </div>

            <div class="stat-card fade-up delay-2" style="--accent-color:#22C55E">
                <div class="stat-top">
                    <span class="stat-label">Productos</span>
                    <div class="stat-icon" style="background:rgba(34,197,94,.15)">
                        <i class="fa-solid fa-box" style="color:#22C55E"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalProducts }}</p>
                <div class="stat-sub"><span>en catálogo activo</span></div>
            </div>

            <div class="stat-card fade-up delay-3" style="--accent-color:#a855f7">
                <div class="stat-top">
                    <span class="stat-label">Pedidos</span>
                    <div class="stat-icon" style="background:rgba(168,85,247,.15)">
                        <i class="fa-solid fa-clipboard-list" style="color:#c084fc"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalOrders }}</p>
                <div class="stat-sub">
                    <span><b style="color:#facc15">{{ $pendingOrders }}</b> pendientes</span>
                    <span><b style="color:#22C55E">{{ $completedOrders }}</b> entregados</span>
                </div>
            </div>

            <div class="stat-card fade-up delay-4" style="--accent-color:#22C55E">
                <div class="stat-top">
                    <span class="stat-label">Ventas totales</span>
                    <div class="stat-icon" style="background:rgba(34,197,94,.15)">
                        <i class="fa-solid fa-bolivar-sign" style="color:#22C55E"></i>
                    </div>
                </div>
                <p class="stat-value green">Bs. {{ number_format($totalSales, 0, '.', ',') }}</p>
                <div class="stat-sub"><span>de pedidos entregados</span></div>
            </div>

        </div>

        <div class="bottom-grid">

            <div class="panel fade-up">
                <div class="panel-head">
                    <h2>
                        <i class="fa-solid fa-trophy" style="color:#facc15"></i>
                        Top Productos Vendidos
                    </h2>
                </div>
                <div class="panel-body">
                    @forelse($topProducts as $i => $product)
                    <div class="top-item">
                        <div class="top-rank"
                            style="background:{{ $i===0 ? 'rgba(250,204,21,.15)' : ($i===1 ? 'rgba(156,163,175,.12)' : ($i===2 ? 'rgba(251,146,60,.15)' : 'rgba(255,255,255,.04)')) }};
                                   color:{{ $i===0 ? '#facc15' : ($i===1 ? '#9ca3af' : ($i===2 ? '#fb923c' : '#4b5563')) }}">
                            {{ $i + 1 }}
                        </div>
                        <span class="top-name">{{ $product->name }}</span>
                        <span class="top-badge">{{ $product->total_sold }} vendidos</span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:36px 0;color:var(--muted)">
                        <i class="fa-solid fa-box-open" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                        <p style="font-size:13px">Sin ventas registradas</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="panel fade-up delay-1">
                <div class="panel-head">
                    <h2>
                        <i class="fa-solid fa-chart-line" style="color:var(--green)"></i>
                        Ventas Últimos 6 Meses
                    </h2>
                </div>
                <div class="panel-body">
                    @php
                        $months = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                        $maxSale = $salesByMonth->max('total') ?: 1;
                    @endphp
                    @forelse($salesByMonth as $sale)
                    <div class="bar-row">
                        <span class="bar-month">{{ $months[$sale->month] ?? $sale->month }}</span>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ max(6, ($sale->total / $maxSale) * 100) }}%"></div>
                        </div>
                        <span class="bar-amount">Bs. {{ number_format($sale->total, 0, '.', ',') }}</span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:36px 0;color:var(--muted)">
                        <i class="fa-solid fa-chart-line" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                        <p style="font-size:13px">Sin datos de ventas</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="quick-grid">

            <a href="{{ route('admin.products.create') }}" class="quick-card" style="--qc-color:rgba(34,197,94,.5)">
                <i class="fa-solid fa-plus quick-icon" style="color:#22C55E"></i>
                <span class="quick-label">Nuevo Producto</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="quick-card" style="--qc-color:rgba(250,204,21,.5)">
                <i class="fa-solid fa-clock quick-icon" style="color:#facc15"></i>
                <span class="quick-label">Pedidos Pendientes</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="quick-card" style="--qc-color:rgba(96,165,250,.5)">
                <i class="fa-solid fa-user-plus quick-icon" style="color:#60a5fa"></i>
                <span class="quick-label">Gestionar Usuarios</span>
            </a>

            @if(Route::has('admin.tickets.index'))
            <a href="{{ route('admin.tickets.index') }}" class="quick-card" style="--qc-color:rgba(192,132,252,.5)">
                <i class="fa-solid fa-ticket quick-icon" style="color:#c084fc"></i>
                <span class="quick-label">Ver Tickets</span>
            </a>
            @endif

            <button onclick="abrirModal()" class="quick-card" style="--qc-color:rgba(239,68,68,.5)">
                <i class="fa-solid fa-user-shield quick-icon" style="color:#f87171"></i>
                <span class="quick-label">Nuevo Admin</span>
            </button>

        </div>

    </main>

</div>

@endsection