<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
body, html { background:#060d0a !important; margin:0; padding:0; }
#ordersPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

.section-label { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--green); margin-bottom:6px; }
.section-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15; }

.filter-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; margin:20px 0; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
.filter-field { display:flex; flex-direction:column; gap:4px; }
.filter-field label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); }
.f-input, .f-select { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:9px 12px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; }
.f-input:focus, .f-select:focus { border-color:var(--green); }
.f-btn { background:var(--green-dark); color:#fff; border:none; border-radius:12px; padding:10px 20px; font-size:13px; font-weight:700; cursor:pointer; }
.f-btn:hover { background:var(--green); }
.f-clear { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:10px 16px; font-size:13px; font-weight:600; text-decoration:none; }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; }
.panel-head span { font-size:11px; color:var(--muted); font-weight:600; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.status-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.st-pending   { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.25); color:#facc15; }
.st-paid      { background:rgba(96,165,250,.12); border:1px solid rgba(96,165,250,.25); color:#60a5fa; }
.st-shipped   { background:rgba(168,85,247,.12); border:1px solid rgba(168,85,247,.25); color:#c084fc; }
.st-delivered { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.25);  color:#4ade80; }
.st-cancelled { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.25);  color:#f87171; }

.status-select { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:10px; padding:6px 10px; font-size:12px; color:#fff; font-family:'DM Sans',sans-serif; }

.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

.fade-up { animation:fadeUp .4s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
</style>

<div id="ordersPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        <div class="fade-up">
            <p class="section-label"><i class="fa-solid fa-receipt mr-1"></i> Gestión</p>
            <h1 class="section-title">Pedidos <span style="color:var(--green)">Realizados</span></h1>
        </div>

        <div class="filter-wrap">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
                <div class="filter-field">
                    <label>Estado</label>
                    <select name="status" class="f-select">
                        <option value="">Todos</option>
                        @foreach(['pending' => 'Pendiente','paid' => 'Pagado','shipped' => 'Enviado','delivered' => 'Entregado','cancelled' => 'Cancelado'] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label>Desde</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="f-input">
                </div>
                <div class="filter-field">
                    <label>Hasta</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="f-input">
                </div>
                <button type="submit" class="f-btn"><i class="fa-solid fa-filter mr-1"></i> Filtrar</button>
                @if(request()->anyFilled(['status','from_date','to_date','user_id']))
                    <a href="{{ route('admin.orders.index') }}" class="f-clear"><i class="fa-solid fa-xmark"></i> Limpiar</a>
                @endif
            </form>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-receipt" style="color:var(--green)"></i> Lista de Pedidos</h2>
                <span>{{ $orders->total() }} pedidos en total</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th style="text-align:center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td style="color:var(--muted);font-size:12px">{{ $order->id }}</td>
                            <td><code style="font-size:12px;color:#e5e7eb">{{ $order->order_number }}</code></td>
                            <td>
                                <p style="font-weight:700;color:#fff">{{ $order->user->name ?? '—' }}</p>
                                <p style="font-size:11px;color:var(--muted)">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td>{{ $order->items->count() }} producto(s)</td>
                            <td style="font-weight:700;color:#fff">Bs {{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="status-badge st-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="font-size:12px;color:var(--muted)">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="display:flex;gap:6px;justify-content:center">
                                    @csrf @method('PATCH')
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        @foreach(['pending','paid','shipped','delivered','cancelled'] as $val)
                                            <option value="{{ $val }}" @selected($order->status === $val)>{{ ucfirst($val) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-receipt"></i>
                                    <p>No se encontraron pedidos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
