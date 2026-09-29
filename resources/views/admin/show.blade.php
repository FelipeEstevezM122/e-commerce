

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
#showPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }

#main { padding:32px 28px 48px; max-width:1100px; margin:0 auto; }

.back-link { display:inline-flex; align-items:center; gap:8px; color:var(--muted); font-size:13px; font-weight:600; text-decoration:none; margin-bottom:24px; transition:color .15s; }
.back-link:hover { color:var(--green); }

.user-hero { background:var(--card); border:1px solid var(--border); border-radius:20px; padding:28px; display:flex; align-items:center; gap:24px; margin-bottom:20px; position:relative; overflow:hidden; }
.user-hero::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--green); opacity:.6; }
.hero-avatar { width:72px; height:72px; border-radius:50%; background:rgba(34,197,94,.15); border:2px solid var(--border-h); display:flex; align-items:center; justify-content:center; font-size:28px; color:var(--green); flex-shrink:0; }
.hero-info h2 { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff; margin-bottom:4px; }
.hero-info p { font-size:13px; color:var(--muted); }
.hero-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.06em; margin-top:8px; }
.rank-final    { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.25);  color:#4ade80; }
.rank-wholesale{ background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.25); color:#facc15; }
.rank-default  { background:rgba(156,163,175,.1); border:1px solid rgba(156,163,175,.2); color:#9ca3af; }
.hero-actions { margin-left:auto; display:flex; gap:10px; flex-shrink:0; }

.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px; }
.info-card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; }
.info-card h3 { font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-bottom:14px; display:flex; align-items:center; gap:7px; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid rgba(255,255,255,.04); }
.info-row:last-child { border-bottom:none; }
.info-key { font-size:12px; color:var(--muted); }
.info-val { font-size:13px; font-weight:600; color:#e5e7eb; text-align:right; }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:14px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }
table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:11px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:12px 18px; font-size:13px; color:#d1d5db; }

.status-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.s-pending   { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.3); color:#facc15; }
.s-paid      { background:rgba(96,165,250,.12); border:1px solid rgba(96,165,250,.3); color:#60a5fa; }
.s-shipped   { background:rgba(168,85,247,.12); border:1px solid rgba(168,85,247,.3); color:#c084fc; }
.s-delivered { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.3);  color:#4ade80; }
.s-cancelled { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.3);  color:#f87171; }

.del-btn { display:inline-flex; align-items:center; gap:7px; padding:8px 16px; background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3); color:#f87171; font-size:12px; font-weight:700; border-radius:10px; cursor:pointer; transition:all .15s; font-family:'DM Sans',sans-serif; }
.del-btn:hover { background:rgba(239,68,68,.25); }

.empty-state { text-align:center; padding:40px; color:var(--muted); }
.empty-state i { font-size:36px; opacity:.3; display:block; margin-bottom:10px; }

@media(max-width:768px) { #main { padding:20px 16px 40px; } .info-grid { grid-template-columns:1fr; } .user-hero { flex-direction:column; text-align:center; } .hero-actions { margin-left:0; } }
</style>

<div id="showPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        <a href="{{ route('admin.users.index') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Volver a Usuarios
        </a>

        <div class="user-hero">
            <div class="hero-avatar"><i class="fa-solid fa-circle-user"></i></div>
            <div class="hero-info">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                @php $rank = $user->rank->name ?? 'Sin rango'; @endphp
                <span class="hero-badge {{ str_contains(strtolower($rank),'mayor') ? 'rank-wholesale' : (str_contains(strtolower($rank),'final') ? 'rank-final' : 'rank-default') }}">
                    <i class="fa-solid fa-star" style="font-size:8px"></i> {{ $rank }}
                </span>
            </div>
            <div class="hero-actions">
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="del-btn">
                        <i class="fa-solid fa-trash"></i> Eliminar usuario
                    </button>
                </form>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3><i class="fa-solid fa-address-card" style="color:var(--green)"></i> Información Personal</h3>
                <div class="info-row">
                    <span class="info-key">Nombre</span>
                    <span class="info-val">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Email</span>
                    <span class="info-val" style="font-size:12px">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Teléfono</span>
                    <span class="info-val">{{ $user->phone ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">WhatsApp</span>
                    <span class="info-val">
                        @if($user->whatsapp)
                            <a href="https://wa.me/{{ $user->whatsapp }}" target="_blank" style="color:#4ade80">
                                <i class="fa-brands fa-whatsapp mr-1"></i>{{ $user->whatsapp }}
                            </a>
                        @else —
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Registrado</span>
                    <span class="info-val">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="info-card">
                <h3><i class="fa-solid fa-shield-halved" style="color:#60a5fa"></i> Cuenta</h3>
                <div class="info-row">
                    <span class="info-key">Código de acceso</span>
                    <span class="info-val">
                        @if($user->access_code)
                            <code style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);padding:2px 8px;border-radius:6px;font-size:11px">{{ $user->access_code }}</code>
                        @else —
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Rango</span>
                    <span class="info-val">{{ $rank }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Total pedidos</span>
                    <span class="info-val" style="color:var(--green);font-weight:800">{{ $user->orders->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Total gastado</span>
                    <span class="info-val" style="color:var(--green);font-weight:800">
                        Bs. {{ number_format($user->orders->where('status','delivered')->sum('total'), 2) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-clipboard-list" style="color:#c084fc"></i> Pedidos del Usuario</h2>
                <span style="font-size:11px;color:var(--muted)">{{ $user->orders->count() }} pedidos</span>
            </div>
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Ticket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders()->latest()->get() as $order)
                        <tr>
                            <td style="font-weight:700;color:#fff;font-family:monospace">{{ $order->order_number }}</td>
                            <td style="font-size:12px;color:var(--muted)">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td style="color:var(--green);font-weight:800">Bs. {{ number_format($order->total, 2) }}</td>
                            <td style="font-size:12px;text-transform:capitalize">{{ $order->payment_method ?? '—' }}</td>
                            <td>
                                @php
                                    $sClass = match($order->status) {
                                        'pending'   => 's-pending',
                                        'paid'      => 's-paid',
                                        'shipped'   => 's-shipped',
                                        'delivered' => 's-delivered',
                                        'cancelled' => 's-cancelled',
                                        default     => 'rank-default',
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
                                <span class="status-badge {{ $sClass }}">{{ $sLabel }}</span>
                            </td>
                            <td>
                                @if($order->ticket)
                                    <code style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);padding:2px 8px;border-radius:6px;font-size:11px;color:#4ade80">
                                        {{ $order->ticket->ticket_number }}
                                    </code>
                                @else
                                    <span style="color:var(--muted);font-size:12px">Sin ticket</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fa-solid fa-clipboard"></i>
                                    <p style="font-size:13px">Este usuario no tiene pedidos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>