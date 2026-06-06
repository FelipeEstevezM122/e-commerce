@extends('layouts.app')

@section('titulo', 'Pedidos - Admin Casatek')

@section('contenido')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
#ordersPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }

/* HEADER */
#adminHeader { background:rgba(6,13,10,.95); backdrop-filter:blur(16px); border-bottom:1px solid var(--border); position:sticky; top:0; z-index:100; padding:0 28px; height:64px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
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

/* MAIN */
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

/* STATUS FILTER TABS */
.status-tabs { display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap; }
.s-tab { display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:10px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:var(--muted); background:rgba(255,255,255,.03); text-decoration:none; transition:all .15s; white-space:nowrap; }
.s-tab:hover { color:#fff; border-color:rgba(255,255,255,.2); }
.s-tab.active { color:#fff; }
.s-tab.t-all      { border-color:rgba(255,255,255,.2); }
.s-tab.t-all.active { background:rgba(255,255,255,.1); }
.s-tab.t-pending.active   { background:rgba(250,204,21,.12); border-color:rgba(250,204,21,.3); color:#facc15; }
.s-tab.t-paid.active      { background:rgba(96,165,250,.12); border-color:rgba(96,165,250,.3); color:#60a5fa; }
.s-tab.t-shipped.active   { background:rgba(168,85,247,.12); border-color:rgba(168,85,247,.3); color:#c084fc; }
.s-tab.t-delivered.active { background:rgba(34,197,94,.12);  border-color:rgba(34,197,94,.3);  color:#4ade80; }
.s-tab.t-cancelled.active { background:rgba(239,68,68,.12);  border-color:rgba(239,68,68,.3);  color:#f87171; }

/* TABLE */
.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }
table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.status-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.s-pending   { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.3); color:#facc15; }
.s-paid      { background:rgba(96,165,250,.12); border:1px solid rgba(96,165,250,.3); color:#60a5fa; }
.s-shipped   { background:rgba(168,85,247,.12); border:1px solid rgba(168,85,247,.3); color:#c084fc; }
.s-delivered { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.3);  color:#4ade80; }
.s-cancelled { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.3);  color:#f87171; }

/* STATUS CHANGE FORM */
.status-form { display:flex; align-items:center; gap:6px; }
.status-select { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); border-radius:8px; padding:5px 10px; font-size:11px; font-weight:600; color:#e5e7eb; font-family:'DM Sans',sans-serif; outline:none; cursor:pointer; transition:border-color .15s; }
.status-select:focus { border-color:var(--green); }
.status-select option { background:#1f2937; }
.update-btn { background:var(--green-dark); border:none; border-radius:8px; padding:5px 10px; color:#fff; font-size:11px; font-weight:700; cursor:pointer; transition:background .15s; white-space:nowrap; }
.update-btn:hover { background:var(--green); }

/* TICKET BTN */
.ticket-btn { display:inline-flex; align-items:center; gap:5px; padding:5px 10px; background:rgba(168,85,247,.12); border:1px solid rgba(168,85,247,.25); color:#c084fc; font-size:11px; font-weight:700; border-radius:8px; cursor:pointer; transition:all .15s; font-family:'DM Sans',sans-serif; }
.ticket-btn:hover { background:rgba(168,85,247,.25); }
.ticket-code { background:rgba(34,197,94,.08); border:1px solid rgba(34,197,94,.2); padding:3px 8px; border-radius:6px; font-size:11px; color:#4ade80; font-family:monospace; }

/* PAGINATION */
.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

@media(max-width:768px) { #adminHeader { padding:0 16px; } .ah-nav { display:none; } #main { padding:20px 16px 40px; } }
</style>

<div id="ordersPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    {{-- HEADER --}}
    <header id="adminHeader">
        <a href="{{ route('admin.dashboard') }}" class="ah-logo">
            <span class="ah-logo-dot"></span>
            <span class="ah-logo-name">Casatek</span>
            <span class="ah-logo-badge">Admin</span>
        </a>
        <nav class="ah-nav">
            <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
            <a href="{{ route('admin.products.index') }}"><i class="fa-solid fa-box"></i> Productos</a>
            <a href="{{ route('admin.orders.index') }}" class="active"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
            <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users"></i> Usuarios</a>
            @if(Route::has('admin.tickets.index'))
            <a href="{{ route('admin.tickets.index') }}"><i class="fa-solid fa-ticket"></i> Tickets</a>
            @endif
        </nav>
        <div class="ah-actions">
            <div class="ah-user">
                <div class="ah-avatar"><i class="fa-solid fa-circle-user"></i></div>
                <span class="ah-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
            <form method="POST" action="{{ route('logout.admin') }}">
                @csrf
                <button type="submit" class="ah-btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Salir
                </button>
            </form>
        </div>
    </header>

    <main id="main">

        {{-- TÍTULO --}}
        <div style="margin-bottom:24px">
            <p style="font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--green);margin-bottom:4px">
                <i class="fa-solid fa-clipboard-list mr-1"></i> Gestión
            </p>
            <h1 style="font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#fff">
                Pedidos
            </h1>
        </div>

        {{-- FILTROS POR ESTADO --}}
        <div class="status-tabs">
            <a href="{{ route('admin.orders.index') }}"
               class="s-tab t-all {{ !request('status') ? 'active' : '' }}">
                <i class="fa-solid fa-border-all"></i> Todos
                <span style="background:rgba(255,255,255,.1);padding:1px 7px;border-radius:10px;font-size:10px">{{ $orders->total() }}</span>
            </a>
            @foreach([
                'pending'   => ['fa-clock',        't-pending',   'Pendientes',  '#facc15'],
                'paid'      => ['fa-credit-card',   't-paid',      'Pagados',     '#60a5fa'],
                'shipped'   => ['fa-truck',         't-shipped',   'Enviados',    '#c084fc'],
                'delivered' => ['fa-circle-check',  't-delivered', 'Entregados',  '#4ade80'],
                'cancelled' => ['fa-ban',           't-cancelled', 'Cancelados',  '#f87171'],
            ] as $val => [$icon, $cls, $label, $color])
            <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
               class="s-tab {{ $cls }} {{ request('status') === $val ? 'active' : '' }}">
                <i class="fa-solid {{ $icon }}"></i> {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- TABLA --}}
        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-clipboard-list" style="color:#c084fc"></i> Lista de Pedidos</h2>
                <span style="font-size:11px;color:var(--muted)">{{ $orders->total() }} pedidos</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Cambiar Estado</th>
                            <th>Ticket</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $sClass = match($order->status) {
                                'pending'   => 's-pending',
                                'paid'      => 's-paid',
                                'shipped'   => 's-shipped',
                                'delivered' => 's-delivered',
                                'cancelled' => 's-cancelled',
                                default     => '',
                            };
                            $sLabel = match($order->status) {
                                'pending'   => 'Pendiente',
                                'paid'      => 'Pagado',
                                'shipped'   => 'Enviado',
                                'delivered' => 'Entregado',
                                'cancelled' => 'Cancelado',
                                default     => $order->status,
                            };
                        @endphp
                        <tr>
                            <td style="font-weight:700;color:#fff;font-family:monospace;font-size:12px">
                                {{ $order->order_number }}
                            </td>
                            <td>
                                <p style="font-weight:600;color:#e5e7eb;font-size:13px">{{ $order->user->name ?? '—' }}</p>
                                <p style="font-size:11px;color:var(--muted)">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td style="color:var(--green);font-weight:800">
                                Bs. {{ number_format($order->total, 2) }}
                            </td>
                            <td style="font-size:12px;text-transform:capitalize;color:#d1d5db">
                                {{ $order->payment_method ?? '—' }}
                            </td>
                            <td>
                                <span class="status-badge {{ $sClass }}">{{ $sLabel }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="status-form">
                                    @csrf @method('PATCH')
                                    <select name="status" class="status-select">
                                        @foreach(['pending'=>'Pendiente','paid'=>'Pagado','shipped'=>'Enviado','delivered'=>'Entregado','cancelled'=>'Cancelado'] as $val => $lbl)
                                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="update-btn">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($order->ticket)
                                    <span class="ticket-code">{{ $order->ticket->ticket_number }}</span>
                                @elseif($order->status === 'delivered')
                                    <form action="{{ route('admin.tickets.generate', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="ticket-btn">
                                            <i class="fa-solid fa-ticket"></i> Generar
                                        </button>
                                    </form>
                                @else
                                    <span style="color:var(--muted);font-size:11px">—</span>
                                @endif
                            </td>
                            <td style="font-size:11px;color:var(--muted)">
                                {{ $order->created_at->format('d/m/Y') }}<br>
                                <span style="font-size:10px">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-clipboard"></i>
                                    <p>No se encontraron pedidos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if($orders->hasPages())
            <div class="pag">
                @if($orders->onFirstPage())
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $orders->withQueryString()->previousPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                @foreach($orders->withQueryString()->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach
                @if($orders->hasMorePages())
                    <a href="{{ $orders->withQueryString()->nextPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
            @endif
        </div>

    </main>
</div>

@endsection