@extends('layouts.admin')

@section('titulo', 'Usuarios - Admin Casatek')

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
#usersPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

.section-label { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--green); margin-bottom:6px; }
.section-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15; }

.mini-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin:20px 0; }
.mini-card { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px 18px; display:flex; align-items:center; gap:14px; }
.mini-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.mini-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
.mini-val { font-family:'Syne',sans-serif; font-size:24px; font-weight:800; color:#fff; line-height:1.1; }

.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; }
.search-input-wrap { position:relative; flex:1; min-width:200px; }
.search-input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:10px 14px 10px 40px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:12px; padding:10px 20px; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; font-family:'DM Sans',sans-serif; }
.s-btn:hover { background:var(--green); }
.s-clear { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:10px 16px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; transition:all .15s; }
.s-clear:hover { color:#fff; background:rgba(255,255,255,.08); }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }
.panel-head span { font-size:11px; color:var(--muted); font-weight:600; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.u-name { font-weight:700; color:#fff; font-size:13px; }
.u-email { font-size:11px; color:var(--muted); margin-top:1px; }

.rank-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.rank-final    { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.25);  color:#4ade80; }
.rank-wholesale{ background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.25); color:#facc15; }
.rank-default  { background:rgba(156,163,175,.1); border:1px solid rgba(156,163,175,.2); color:#9ca3af; }

.act-btn { width:32px; height:32px; border-radius:9px; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-size:13px; transition:all .15s; text-decoration:none; }
.act-view { background:rgba(96,165,250,.12); color:#60a5fa; border:1px solid rgba(96,165,250,.25); }
.act-view:hover { background:rgba(96,165,250,.25); }
.act-del  { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); }
.act-del:hover  { background:rgba(239,68,68,.25); }

.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }
.empty-state p { font-size:14px; }

.fade-up { animation:fadeUp .4s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.delay-1 { animation-delay:.07s; } .delay-2 { animation-delay:.14s; } .delay-3 { animation-delay:.21s; }

/* MODAL */
#userModal {
    display:none; position:fixed; inset:0; z-index:999;
    background:rgba(0,0,0,.7); backdrop-filter:blur(4px);
    align-items:center; justify-content:center; padding:20px;
}
#userModal.open { display:flex; }
#userModal .modal-box {
    background:#111f16; border:1px solid rgba(34,197,94,.25);
    border-radius:20px; width:100%; max-width:480px; overflow:hidden;
    box-shadow:0 24px 60px rgba(0,0,0,.6);
    animation:modalIn .2s ease;
}
@keyframes modalIn { from { opacity:0; transform:translateY(12px) scale(.97); } to { opacity:1; transform:translateY(0) scale(1); } }
.modal-header { padding:20px 24px; border-bottom:1px solid rgba(34,197,94,.12); display:flex; align-items:center; justify-content:space-between; }
.modal-body { padding:24px; display:grid; gap:12px; }
.modal-footer { padding:16px 24px; border-top:1px solid rgba(34,197,94,.12); display:flex; justify-content:flex-end; }
.modal-field { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); border-radius:12px; padding:14px; }
.modal-field-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:4px; }
.modal-field-val { font-size:13px; color:#d1d5db; font-weight:600; }
.modal-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.modal-close-btn { width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; transition:all .15s; }
.modal-close-btn:hover { background:rgba(255,255,255,.1); color:#fff; }

@media(max-width:900px) { .mini-stats { grid-template-columns:1fr 1fr; } }
@media(max-width:768px) { #main { padding:20px 16px 40px; } .mini-stats { grid-template-columns:1fr 1fr; } }
</style>

<div id="usersPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        <div class="fade-up">
            <p class="section-label"><i class="fa-solid fa-users mr-1"></i> Gestión</p>
            <h1 class="section-title">Usuarios <span style="color:var(--green)">Registrados</span></h1>
        </div>

        <div class="mini-stats">
            <div class="mini-card fade-up delay-1">
                <div class="mini-icon" style="background:rgba(59,130,246,.15)">
                    <i class="fa-solid fa-users" style="color:#60a5fa"></i>
                </div>
                <div>
                    <p class="mini-label">Total usuarios</p>
                    <p class="mini-val">{{ $users->total() }}</p>
                </div>
            </div>
            <div class="mini-card fade-up delay-2">
                <div class="mini-icon" style="background:rgba(34,197,94,.15)">
                    <i class="fa-solid fa-user-check" style="color:#22C55E"></i>
                </div>
                <div>
                    <p class="mini-label">Clientes finales</p>
                    <p class="mini-val">{{ $finalCustomers ?? '—' }}</p>
                </div>
            </div>
            <div class="mini-card fade-up delay-3">
                <div class="mini-icon" style="background:rgba(250,204,21,.15)">
                    <i class="fa-solid fa-store" style="color:#facc15"></i>
                </div>
                <div>
                    <p class="mini-label">Mayoristas</p>
                    <p class="mini-val">{{ $wholesalers ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="search-wrap">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:12px;flex:1;flex-wrap:wrap">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por nombre, email o teléfono..." class="s-input">
                </div>
                <button type="submit" class="s-btn"><i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar</button>
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="s-clear"><i class="fa-solid fa-xmark"></i> Limpiar</a>
                @endif
            </form>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-users" style="color:var(--green)"></i> Lista de Usuarios</h2>
                <span>{{ $users->total() }} usuarios en total</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>WhatsApp</th>
                            <th>Código Acceso</th>
                            <th>Rango</th>
                            <th>Registro</th>
                            <th style="text-align:center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td style="color:var(--muted);font-size:12px">{{ $user->id }}</td>
                            <td>
                                <p class="u-name">{{ $user->name }}</p>
                                <p class="u-email">{{ $user->email }}</p>
                            </td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td>
                                @if($user->whatsapp)
                                    <a href="https://wa.me/{{ $user->whatsapp }}" target="_blank"
                                       style="color:#4ade80;font-size:13px;display:flex;align-items:center;gap:5px">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $user->whatsapp }}
                                    </a>
                                @else
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->access_code)
                                    <code style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);padding:2px 8px;border-radius:6px;font-size:11px;color:#e5e7eb">
                                        {{ $user->access_code }}
                                    </code>
                                @else
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td>
                                @php $rank = $user->rank->name ?? 'default'; @endphp
                                <span class="rank-badge {{ str_contains(strtolower($rank),'mayor') ? 'rank-wholesale' : (str_contains(strtolower($rank),'final') ? 'rank-final' : 'rank-default') }}">
                                    <i class="fa-solid fa-star" style="font-size:8px"></i> {{ $rank }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:var(--muted)">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display:flex;justify-content:center;gap:6px">
                                    {{-- Botón ver → abre modal --}}
                                    <button type="button" class="act-btn act-view" title="Ver detalle"
                                        onclick="openUserModal({
                                            id: '{{ $user->id }}',
                                            name: '{{ addslashes($user->name) }}',
                                            email: '{{ addslashes($user->email) }}',
                                            phone: '{{ addslashes($user->phone ?? '—') }}',
                                            whatsapp: '{{ $user->whatsapp ?? '' }}',
                                            access_code: '{{ $user->access_code ?? '—' }}',
                                            rank: '{{ addslashes($user->rank->name ?? 'Sin rango') }}',
                                            created_at: '{{ $user->created_at->format('d/m/Y') }}'
                                        })">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn act-del" title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-users-slash"></i>
                                    <p>No se encontraron usuarios</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="pag">
                @if($users->onFirstPage())
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $users->withQueryString()->previousPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                @foreach($users->withQueryString()->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach
                @if($users->hasMorePages())
                    <a href="{{ $users->withQueryString()->nextPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
            <p style="text-align:center;font-size:11px;color:var(--muted);padding-bottom:14px">
                Página {{ $users->currentPage() }} de {{ $users->lastPage() }}
            </p>
            @endif
        </div>

    </main>
</div>

 <!-- USUARIO  -->
<div id="userModal">
    <div class="modal-box">

        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(96,165,250,.15);display:flex;align-items:center;justify-content:center">
                    <i class="fa-solid fa-user" style="color:#60a5fa;font-size:14px"></i>
                </div>
                <div>
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)">Detalle de usuario</p>
                    <p id="modal-name" style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#fff"></p>
                </div>
            </div>
            <button class="modal-close-btn" onclick="closeUserModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">

            <div class="modal-grid-2">
                <div class="modal-field">
                    <p class="modal-field-label">ID</p>
                    <p id="modal-id" class="modal-field-val"></p>
                </div>
                <div class="modal-field">
                    <p class="modal-field-label">Registro</p>
                    <p id="modal-date" class="modal-field-val"></p>
                </div>
            </div>

            <div class="modal-field">
                <p class="modal-field-label">Email</p>
                <p id="modal-email" class="modal-field-val"></p>
            </div>

            <div class="modal-grid-2">
                <div class="modal-field">
                    <p class="modal-field-label">Teléfono</p>
                    <p id="modal-phone" class="modal-field-val"></p>
                </div>
                <div class="modal-field">
                    <p class="modal-field-label">WhatsApp</p>
                    <p id="modal-whatsapp" class="modal-field-val"></p>
                </div>
            </div>

            <div class="modal-grid-2">
                <div class="modal-field">
                    <p class="modal-field-label">Código de acceso</p>
                    <p id="modal-code" class="modal-field-val" style="font-family:monospace"></p>
                </div>
                <div class="modal-field">
                    <p class="modal-field-label">Rango</p>
                    <p id="modal-rank" class="modal-field-val" style="color:var(--green)"></p>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button onclick="closeUserModal()"
                style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#9ca3af;border-radius:10px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#9ca3af'">
                Cerrar
            </button>
        </div>

    </div>
</div>

<script>
function openUserModal(u) {
    document.getElementById('modal-name').textContent    = u.name;
    document.getElementById('modal-id').textContent      = '#' + u.id;
    document.getElementById('modal-date').textContent    = u.created_at;
    document.getElementById('modal-email').textContent   = u.email;
    document.getElementById('modal-phone').textContent   = u.phone;
    document.getElementById('modal-code').textContent    = u.access_code;
    document.getElementById('modal-rank').textContent    = u.rank;
    document.getElementById('modal-whatsapp').innerHTML  = u.whatsapp
        ? `<a href="https://wa.me/${u.whatsapp}" target="_blank" style="color:#4ade80;display:flex;align-items:center;gap:5px"><i class="fa-brands fa-whatsapp"></i> ${u.whatsapp}</a>`
        : '—';
    document.getElementById('userModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeUserModal() {
    document.getElementById('userModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) closeUserModal();
});
// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeUserModal();
});
</script>

@endsection
