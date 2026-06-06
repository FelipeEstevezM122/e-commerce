
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
#productsPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; position:relative; overflow:hidden; }
.stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--accent, var(--green)); opacity:.7; }
.stat-label { font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.stat-value { font-family:'Syne',sans-serif; font-size:36px; font-weight:800; color:#fff; line-height:1; }

.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; margin-bottom:20px; }
.search-inner { display:flex; gap:12px; flex-wrap:wrap; }
.search-input-wrap { position:relative; flex:1; min-width:200px; }
.search-input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:10px 14px 10px 40px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
select.s-input { padding-left:14px; }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:12px; padding:10px 20px; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; font-family:'DM Sans',sans-serif; }
.s-btn:hover { background:var(--green); }
.s-clear { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:10px 16px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; transition:all .15s; }
.s-clear:hover { color:#fff; }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.stock-badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.stock-ok     { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.3);  color:#4ade80; }
.stock-low    { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.3); color:#facc15; }
.stock-none   { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.3);  color:#f87171; }

.act-btn { width:34px; height:34px; border-radius:10px; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-size:13px; transition:all .15s; text-decoration:none; }
.act-edit { background:rgba(96,165,250,.12); color:#60a5fa; border:1px solid rgba(96,165,250,.25); }
.act-edit:hover { background:rgba(96,165,250,.25); }
.act-del  { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); }
.act-del:hover  { background:rgba(239,68,68,.25); }

.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

#adminToast { opacity:0; transform:translateY(8px); transition:opacity .2s, transform .2s; pointer-events:none; }
#adminToast.show { opacity:1; transform:translateY(0); }

@media(max-width:1100px) { .stat-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px) { #main { padding:20px 16px 40px; } .stat-grid { grid-template-columns:1fr 1fr; } }
</style>

<div id="productsPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        {{-- TÍTULO --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
            <div>
                <p style="font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--green);margin-bottom:4px">
                    <i class="fa-solid fa-box mr-1"></i> Catálogo
                </p>
                <h1 style="font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#fff">Panel de Productos</h1>
                <p style="font-size:12px;color:var(--muted);margin-top:2px">Administración general del catálogo Casatek</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--green);color:#fff;font-weight:700;font-size:13px;padding:11px 22px;border-radius:12px;text-decoration:none;transition:background .15s"
               onmouseover="this.style.background='var(--green-dark)'" onmouseout="this.style.background='var(--green)'">
                <i class="fa-solid fa-plus"></i> Agregar Producto
            </a>
        </div>

        {{-- MENSAJES --}}
        @if(session('success'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-circle-check"></i>{{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px">
            @foreach($errors->all() as $error)
                <p><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- STATS --}}
        <div class="stat-grid">
            <div class="stat-card" style="--accent:var(--green)">
                <p class="stat-label">Productos</p>
                <p class="stat-value">{{ $totalProducts }}</p>
            </div>
            <div class="stat-card" style="--accent:#3b82f6">
                <p class="stat-label">Marcas</p>
                <p class="stat-value">{{ $totalBrands }}</p>
            </div>
            <div class="stat-card" style="--accent:#facc15">
                <p class="stat-label">Stock Bajo</p>
                <p class="stat-value">{{ $lowStock }}</p>
            </div>
            <div class="stat-card" style="--accent:#ef4444">
                <p class="stat-label">Sin Stock</p>
                <p class="stat-value">{{ $noStock }}</p>
            </div>
        </div>

        {{-- BUSCADOR --}}
        <div class="search-wrap">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="search-inner">
                    <div class="search-input-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar producto..." class="s-input">
                    </div>
                    <select name="category_id" class="s-input" style="width:200px;padding-left:14px">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="s-btn"><i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar</button>
                    @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}" class="s-clear"><i class="fa-solid fa-xmark"></i> Limpiar</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-table-list" style="color:var(--green)"></i> Gestión de Productos</h2>
                <span style="font-size:11px;color:var(--muted)">{{ $products->total() }} productos en total</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th style="text-align:center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image1 ?? 'https://via.placeholder.com/56' }}"
                                     class="w-14 h-14 rounded-xl object-cover"
                                     style="border:1px solid rgba(255,255,255,.08)"
                                     onerror="this.src='https://via.placeholder.com/56'">
                            </td>
                            <td>
                                <p style="font-weight:700;color:#fff;font-size:13px">{{ $product->name }}</p>
                                <p style="font-size:11px;color:var(--muted);margin-top:2px">SKU: {{ $product->sku }}</p>
                            </td>
                            <td style="font-weight:600;color:#e5e7eb">{{ $product->brand->name ?? '—' }}</td>
                            <td>
                                <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--green)">
                                    {{ $product->category->name ?? '—' }}
                                </span>
                            </td>
                            <td style="color:var(--green);font-weight:800">Bs. {{ number_format($product->base_price, 2) }}</td>
                            <td style="font-weight:800;color:{{ $product->stock <= 10 ? '#f87171' : '#fff' }}">
                                {{ $product->stock }}
                            </td>
                            <td>
                                @if($product->stock === 0)
                                    <span class="stock-badge stock-none">Sin Stock</span>
                                @elseif($product->stock <= 10)
                                    <span class="stock-badge stock-low">Stock Bajo</span>
                                @else
                                    <span class="stock-badge stock-ok">Activo</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;justify-content:center;gap:6px">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="act-btn act-edit" title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar {{ addslashes($product->name) }}?')">
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
                                    <i class="fa-solid fa-box-open"></i>
                                    <p style="font-size:14px;font-weight:600">No se encontraron productos</p>
                                    <a href="{{ route('admin.products.index') }}" style="color:var(--green);font-size:13px;font-weight:700;text-decoration:none;display:inline-block;margin-top:8px">
                                        Ver todos los productos
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="pag">
                @if($products->onFirstPage())
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $products->withQueryString()->previousPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                @foreach($products->withQueryString()->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach
                @if($products->hasMorePages())
                    <a href="{{ $products->withQueryString()->nextPageUrl() }}" class="pag-btn"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
            <p style="text-align:center;font-size:11px;color:var(--muted);padding-bottom:14px">
                Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
            </p>
            @endif
        </div>

    </main>
</div>

{{-- Toast --}}
<div id="adminToast"
     class="fixed bottom-6 right-6 z-[200]"
     style="background:#111f16;border:1px solid rgba(34,197,94,.3);color:#fff;font-size:13px;font-weight:600;padding:12px 18px;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,.5);display:flex;align-items:center;gap:8px;">
    <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
    <span id="adminToastMsg">Acción completada</span>
</div>

<script>
@if(session('success'))
    (function(){
        const t = document.getElementById('adminToast');
        document.getElementById('adminToastMsg').textContent = '{{ session('success') }}';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    })();
@endif
</script>

