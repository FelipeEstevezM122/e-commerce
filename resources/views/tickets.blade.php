@extends('layouts.admin')

@section('titulo', 'Tickets - Admin Casatek')

@section('contenido')


<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
body, html {
    background: #060d0a !important;
    margin: 0;
    padding: 0;
}
#ticketsPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1200px; margin:0 auto; }

.section-label { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#c084fc; margin-bottom:6px; }
.section-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15; }

.mini-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin:20px 0; }
.mini-card { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px 18px; display:flex; align-items:center; gap:14px; }
.mini-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.mini-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
.mini-val { font-family:'Syne',sans-serif; font-size:24px; font-weight:800; color:#fff; line-height:1.1; }

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

.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px 18px; margin-bottom:18px; display:flex; gap:10px; }
.search-input-wrap { position:relative; flex:1; }
.search-input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:10px; padding:9px 14px 9px 38px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:13px; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .15s; }
.s-btn:hover { background:var(--green); }

.fade-up { animation:fadeUp .4s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.delay-1 { animation-delay:.07s; } .delay-2 { animation-delay:.14s; } .delay-3 { animation-delay:.21s; }

@media(max-width:768px) { #main { padding:20px 16px 40px; } .mini-stats { grid-template-columns:1fr; } }
</style>

<div id="ticketsPage">

    @include('partials.header-admin')

    <main id="main">

        <div class="fade-up">
            <p class="section-label"><i class="fa-solid fa-ticket mr-1"></i> Comprobantes</p>
            <h1 class="section-title">Tickets <span style="color:#c084fc">Generados</span></h1>
        </div>

        <div class="mini-stats">
            <div class="mini-card fade-up delay-1">
                <div class="mini-icon" style="background:rgba(168,85,247,.15)">
                    <i class="fa-solid fa-ticket" style="color:#c084fc"></i>
                </div>
                <div>
                    <p class="mini-label">Total tickets</p>
                    <p class="mini-val">{{ $tickets->total() }}</p>
                </div>
            </div>
            <div class="mini-card fade-up delay-2">
                <div class="mini-icon" style="background:rgba(34,197,94,.15)">
                    <i class="fa-solid fa-calendar-day" style="color:var(--green)"></i>
                </div>
                <div>
                    <p class="mini-label">Hoy</p>
                    <p class="mini-val">{{ $tickets->getCollection()->filter(fn($t) => $t->issued_at->isToday())->count() }}</p>
                </div>
            </div>
            <div class="mini-card fade-up delay-3">
                <div class="mini-icon" style="background:rgba(96,165,250,.15)">
                    <i class="fa-solid fa-calendar-week" style="color:#60a5fa"></i>
                </div>
                <div>
                    <p class="mini-label">Esta semana</p>
                    <p class="mini-val">{{ $tickets->getCollection()->filter(fn($t) => $t->issued_at->isCurrentWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="search-wrap">
            <form method="GET" action="{{ route('admin.tickets.index') }}" style="display:flex;gap:10px;flex:1">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por número de ticket o pedido..." class="s-input">
                </div>
                <button type="submit" class="s-btn"><i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar</button>
                @if(request('search'))
                <a href="{{ route('admin.tickets.index') }}"
                   style="display:flex;align-items:center;gap:6px;padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#9ca3af;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">
                    <i class="fa-solid fa-xmark"></i> Limpiar
                </a>
                @endif
            </form>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-ticket" style="color:#c084fc"></i> Lista de Tickets</h2>
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
                            <td><span class="ticket-num">{{ $ticket->ticket_number }}</span></td>
                            <td><span class="order-num">{{ $ticket->order->order_number ?? '—' }}</span></td>
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
