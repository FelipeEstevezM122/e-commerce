@extends('layouts.app')

@section('titulo', 'Tickets - Admin Casatek')

@section('contenido')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
#ticketsPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }

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
#main { padding:32px 28px 48px; max-width:1200px; margin:0 auto; }

/* STAT MINI */
.mini-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
.mini-card { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px 18px; display:flex; align-items:center; gap:14px; }
.mini-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.mini-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
.mini-val { font-family:'Syne',sans-serif; font-size:24px; font-weight:800; color:#fff; line-height:1.1; }

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

.ticket-num { font-family:monospace; font-size:13px; font-weight:700; color:#fff; background:rgba(168,85,247,.1); border:1px solid rgba(168,85,247,.25); padding:4px 10px; border-radius:8px; display:inline-block; }
.order-num  { font-family:monospace; font-size:12px; color:var(--green); }

/* PAGINATION */
.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

/* SEARCH */
.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px 18px; margin-bottom:18px; display:flex; gap:10px; }
.search-input-wrap { position:relative; flex:1; }
.search-input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:10px; padding:9px 14px 9px 38px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:13px; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .15s; }
.s-btn:hover { background:var(--green); }

@media(max-width:768px) { #adminHeader { padding:0 16px; } .ah-nav { display:none; } #main { padding:20px 16px 40px; } .mini-stats { grid-template-columns:1fr; } }
</style>

<div id="ticketsPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

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
            <a href="{{ route('admin.orders.index') }}"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
            <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users"></i> Usuarios</a>
            <a href="{{ route('admin.tickets.index') }}" class="active"><i class="fa-solid fa-ticket"></i> Tickets</a>
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
            <p style="font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#c084fc;margin-bottom:4px">
                <i class="fa-solid fa-ticket mr-1"></i> Comprobantes
            </p>
            <h1 style="font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#fff">
                Tickets Generados
            </h1>
        </div>

        {{-- MINI STATS --}}
        <div class="mini-stats">
            <div class="mini-card">
                <div class="mini-icon" style="background:rgba(168,85,247,.15)">
                    <i class="fa-solid fa-ticket" style="color:#c084fc"></i>
                </div>
                <div>
                    <p class="mini-label">Total tickets</p>
                    <p class="mini-val">{{ $tickets->total() }}</p>
                </div>
            </div>
            <div class="mini-card">
                <div class="mini-icon" style="background:rgba(34,197,94,.15)">
                    <i class="fa-solid fa-calendar-day" style="color:var(--green)"></i>
                </div>
                <div>
                    <p class="mini-label">Hoy</p>
                    <p class="mini-val">{{ $tickets->getCollection()->filter(fn($t) => $t->issued_at->isToday())->count() }}</p>
                </div>
            </div>
            <div class="mini-card">
                <div class="mini-icon" style="background:rgba(96,165,250,.15)">
                    <i class="fa-solid fa-calendar-week" style="color:#60a5fa"></i>
                </div>
                <div>
                    <p class="mini-label">Esta semana</p>
                    <p class="mini-val">{{ $tickets->getCollection()->filter(fn($t) => $t->issued_at->isCurrentWeek())->count() }}</p>
                </div>
            </div>
        </div>

        {{-- BUSCADOR --}}
        <div class="search-wrap">
            <form method="GET" action="{{ route('admin.tickets.index') }}" style="display:flex;gap:10px;flex:1">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por número de ticket o pedido..."
                           class="s-input">
                </div>
                <button type="submit" class="s-btn">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
                </button>
                @if(request('search'))
                <a href="{{ route('admin.tickets.index') }}"
                   style="display:flex;align-items:center;gap:6px;padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#9ca3af;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:all .15s">
                    <i class="fa-solid fa-xmark"></i> Limpiar
                </a>
                @endif
            </form>
        </div>

        {{-- TABLA --}}
        <div class="panel">
            <div class="panel-head">
                <h2>
                    <i class="fa-solid fa-ticket" style="color:#c084fc"></i> Lista de Tickets
                </h2>
                <span style="font-size:11px;color:var(--muted)">{{ $tickets->total() }} tickets</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Ticket</th>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Total Pedido</th>
                            <th>Fecha Emisión</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <span class="ticket-num">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td>
                                <span class="order-num">{{ $ticket->order->order_number ?? '—' }}</span>
                            </td>
                            <td>
                                @if($ticket->order && $ticket->order->user)
                                    <p style="font-weight:600;color:#e5e7eb;font-size:13px">{{ $ticket->order->user->name }}</p>
                                    <p style="font-size:11px;color:var(--muted)">{{ $ticket->order->user->email }}</p>
                                @else
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td style="color:var(--green);font-weight:800">
                                Bs. {{ $ticket->order ? number_format($ticket->order->total, 2) : '—' }}
                            </td>
                            <td>
                                <p style="font-size:13px;color:#e5e7eb">{{ $ticket->issued_at->format('d/m/Y') }}</p>
                                <p style="font-size:11px;color:var(--muted)">{{ $ticket->issued_at->format('H:i') }}</p>
                            </td>
                            <td style="color:var(--muted);font-size:12px">
                                {{ $ticket->order ? $ticket->order->items->count() . ' producto(s)' : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fa-solid fa-ticket"></i>
                                    <p>No hay tickets generados aún</p>
                                    <p style="font-size:12px;margin-top:6px">Los tickets se generan desde la sección de Pedidos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if($tickets->hasPages())
            <div class="pag">
                @if($tickets->onFirstPage())
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $tickets->withQueryString()->previousPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                @foreach($tickets->withQueryString()->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                    @if($page == $tickets->currentPage())
                        <span class="pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach
                @if($tickets->hasMorePages())
                    <a href="{{ $tickets->withQueryString()->nextPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
            <p style="text-align:center;font-size:11px;color:var(--muted);padding-bottom:14px">
                Página {{ $tickets->currentPage() }} de {{ $tickets->lastPage() }}
            </p>
            @endif
        </div>

    </main>
</div>
@endsection