<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
body, html { background:#060d0a !important; margin:0; padding:0; }
#productsPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

.section-label { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--green); margin-bottom:6px; }
.section-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15; }

.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin:28px 0; }
.stat-card { background:var(--card); border:1px solid var(--border); border-radius:18px; padding:22px 20px; transition:border-color .2s,transform .2s; position:relative; overflow:hidden; }
.stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--accent-color,var(--green)); opacity:.7; }
.stat-card:hover { border-color:var(--border-h); transform:translateY(-2px); }
.stat-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
.stat-label { font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
.stat-icon { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:16px; }
.stat-value { font-family:'Syne',sans-serif; font-size:36px; font-weight:800; color:#fff; line-height:1; margin-bottom:8px; }
.stat-sub { font-size:11px; color:var(--muted); }

.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; margin-bottom:20px; }
.search-inner { display:flex; gap:12px; flex-wrap:wrap; }
.search-input-wrap { position:relative; flex:1; min-width:180px; }
.search-input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:10px 14px 10px 40px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; box-sizing:border-box; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
select.s-input { padding-left:14px; }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:12px; padding:10px 20px; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; font-family:'DM Sans',sans-serif; white-space:nowrap; }
.s-btn:hover { background:var(--green); }
.s-clear { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:10px 16px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; transition:all .15s; white-space:nowrap; }
.s-clear:hover { color:#fff; }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.stock-badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; }
.stock-ok   { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.3);  color:#4ade80; }
.stock-low  { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.3); color:#facc15; }
.stock-none { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.3);  color:#f87171; }

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

#adminToast { opacity:0; transform:translateY(8px); transition:opacity .2s,transform .2s; pointer-events:none; }
#adminToast.show { opacity:1; transform:translateY(0); }

.fade-up { animation:fadeUp .4s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.delay-1{animation-delay:.07s} .delay-2{animation-delay:.14s}
.delay-3{animation-delay:.21s} .delay-4{animation-delay:.28s}

/* ── Modal ── */
#modalEditProduct { display:none; position:fixed; inset:0; background:rgba(0,0,0,.8); backdrop-filter:blur(8px); z-index:300; align-items:center; justify-content:center; padding:20px; }
#modalEditProduct.open { display:flex; }
.modal-box { background:#0e1a12; border:1px solid rgba(34,197,94,.2); border-radius:22px; width:100%; max-width:640px; max-height:90vh; overflow-y:auto; box-shadow:0 24px 80px rgba(0,0,0,.7); animation:modal-in .22s ease; }
@keyframes modal-in { from{transform:scale(.94);opacity:0} to{transform:scale(1);opacity:1} }
.modal-head { padding:18px 22px; border-bottom:1px solid rgba(34,197,94,.12); display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; background:#0e1a12; z-index:1; }
.modal-head h3 { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }
.modal-close { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); border-radius:8px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#6b7280; cursor:pointer; font-size:15px; transition:all .15s; }
.modal-close:hover { background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.3); color:#f87171; }
.modal-body { padding:22px; }
.m-label { display:block; font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.m-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:10px 14px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; box-sizing:border-box; }
.m-input:focus { border-color:var(--green); }
.m-input::placeholder { color:rgba(255,255,255,.2); }
select.m-input option { background:#1f2937; }
textarea.m-input { resize:vertical; }
.m-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px; }
.m-field { margin-bottom:16px; }
.m-footer { display:flex; gap:10px; padding:18px 22px; border-top:1px solid rgba(34,197,94,.12); }
.btn-save { flex:1; background:var(--green); color:#fff; border:none; border-radius:12px; padding:11px 20px; font-size:13px; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-save:hover { background:var(--green-dark); }
.btn-save:disabled { opacity:.5; cursor:not-allowed; }
.btn-cancel-m { flex:1; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:11px 20px; font-size:13px; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .15s; }
.btn-cancel-m:hover { color:#fff; background:rgba(255,255,255,.08); }
.img-preview-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-top:8px; }
.img-preview-item img { width:100%; aspect-ratio:1; object-fit:cover; border-radius:10px; border:1px solid rgba(255,255,255,.08); }
.img-preview-item p { font-size:10px; color:var(--muted); margin-top:4px; text-align:center; }
.file-input { width:100%; background:rgba(255,255,255,.03); border:1px dashed rgba(255,255,255,.15); border-radius:10px; padding:8px 12px; font-size:12px; color:#9ca3af; cursor:pointer; box-sizing:border-box; }
.file-input:hover { border-color:var(--green); }
#modalErrorMsg { display:none; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#f87171; padding:10px 14px; border-radius:10px; font-size:12px; margin-bottom:14px; }

/* ── Responsive ── */
@media(max-width:1100px) { .stat-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px) {
    #main { padding:20px 14px 40px; }
    .stat-grid { grid-template-columns:1fr 1fr; }
    .m-row { grid-template-columns:1fr; }
    th.hide-sm, td.hide-sm { display:none; }
    .img-preview-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:480px) {
    .stat-grid { grid-template-columns:1fr 1fr; }
    .stat-value { font-size:28px; }
    .section-title { font-size:22px; }
    th.hide-xs, td.hide-xs { display:none; }
}
</style>

<div id="productsPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;flex-wrap:wrap;gap:12px" class="fade-up">
            <div>
                <p class="section-label"><i class="fa-solid fa-box mr-1"></i> Catálogo</p>
                <h1 class="section-title">Panel de <span style="color:var(--green)">Productos</span></h1>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Administración general del catálogo Casatek</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--green);color:#fff;font-weight:700;font-size:13px;padding:11px 22px;border-radius:12px;text-decoration:none;transition:background .15s;margin-top:8px;white-space:nowrap;"
               onmouseover="this.style.background='var(--green-dark)'" onmouseout="this.style.background='var(--green)'">
                <i class="fa-solid fa-plus"></i> Agregar Producto
            </a>
        </div>

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

        <div class="stat-grid">
            <div class="stat-card fade-up delay-1" style="--accent-color:var(--green)">
                <div class="stat-top">
                    <span class="stat-label">Productos</span>
                    <div class="stat-icon" style="background:rgba(34,197,94,.15)"><i class="fa-solid fa-box" style="color:#22C55E"></i></div>
                </div>
                <p class="stat-value">{{ $totalProducts }}</p>
                <div class="stat-sub">en catálogo activo</div>
            </div>
            <div class="stat-card fade-up delay-2" style="--accent-color:#3b82f6">
                <div class="stat-top">
                    <span class="stat-label">Marcas</span>
                    <div class="stat-icon" style="background:rgba(59,130,246,.15)"><i class="fa-solid fa-tag" style="color:#60a5fa"></i></div>
                </div>
                <p class="stat-value">{{ $totalBrands }}</p>
                <div class="stat-sub">marcas registradas</div>
            </div>
            <div class="stat-card fade-up delay-3" style="--accent-color:#facc15">
                <div class="stat-top">
                    <span class="stat-label">Stock Bajo</span>
                    <div class="stat-icon" style="background:rgba(250,204,21,.15)"><i class="fa-solid fa-triangle-exclamation" style="color:#facc15"></i></div>
                </div>
                <p class="stat-value">{{ $lowStock }}</p>
                <div class="stat-sub">productos con poco stock</div>
            </div>
            <div class="stat-card fade-up delay-4" style="--accent-color:#ef4444">
                <div class="stat-top">
                    <span class="stat-label">Sin Stock</span>
                    <div class="stat-icon" style="background:rgba(239,68,68,.15)"><i class="fa-solid fa-ban" style="color:#f87171"></i></div>
                </div>
                <p class="stat-value">{{ $noStock }}</p>
                <div class="stat-sub">productos agotados</div>
            </div>
        </div>

        <div class="search-wrap">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="search-inner">
                    <div class="search-input-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto..." class="s-input">
                    </div>
                    <select name="category_id" class="s-input" style="width:200px;padding-left:14px;max-width:100%">
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
                            <th class="hide-sm">Marca</th>
                            <th class="hide-sm">Categoría</th>
                            <th>Precio</th>
                            <th class="hide-xs">Stock</th>
                            <th class="hide-xs">Estado</th>
                            <th style="text-align:center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image1 ?? 'https://via.placeholder.com/56' }}"
                                     style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid rgba(255,255,255,.08)"
                                     onerror="this.src='https://via.placeholder.com/56'">
                            </td>
                            <td>
                                <p style="font-weight:700;color:#fff;font-size:13px;margin:0">{{ $product->name }}</p>
                                <p style="font-size:11px;color:var(--muted);margin:2px 0 0">SKU: {{ $product->sku }}</p>
                            </td>
                            <td class="hide-sm" style="font-weight:600;color:#e5e7eb">{{ $product->brand->name ?? '—' }}</td>
                            <td class="hide-sm">
                                <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--green)">
                                    {{ $product->category->name ?? '—' }}
                                </span>
                            </td>
                            <td style="color:var(--green);font-weight:800;white-space:nowrap">Bs. {{ number_format($product->base_price, 2) }}</td>
                            <td class="hide-xs" style="font-weight:800;color:{{ $product->stock <= 10 ? '#f87171' : '#fff' }}">{{ $product->stock }}</td>
                            <td class="hide-xs">
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
                                    <button type="button" class="act-btn act-edit" title="Editar"
                                        onclick='abrirModalEditar({
                                            id:           {{ $product->id }},
                                            name:         {{ json_encode($product->name) }},
                                            sku:          {{ json_encode($product->sku) }},
                                            base_price:   {{ $product->base_price }},
                                            stock:        {{ $product->stock }},
                                            warranty_days:{{ $product->warranty_days ?? 0 }},
                                            description:  {{ json_encode($product->description ?? "") }},
                                            brand_name:   {{ json_encode($product->brand->name ?? "") }},
                                            category_name:{{ json_encode($product->category->name ?? "") }},
                                            image1: {{ json_encode($product->image1 ?? "") }},
                                            image2: {{ json_encode($product->image2 ?? "") }},
                                            image3: {{ json_encode($product->image3 ?? "") }},
                                            image4: {{ json_encode($product->image4 ?? "") }}
                                        })'>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
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

{{-- ══ MODAL EDITAR ══ --}}
<div id="modalEditProduct" onclick="cerrarModalEdit(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-head">
            <h3><i class="fa-solid fa-pen-to-square" style="color:#60a5fa"></i> Editar Producto</h3>
            <button type="button" class="modal-close" onclick="cerrarModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="formEditWrapper">
            <div class="modal-body">
                <div id="modalErrorMsg"></div>
                <div class="m-field">
                    <label class="m-label">Nombre del Producto *</label>
                    <input type="text" id="edit_name" class="m-input" placeholder="Nombre del producto">
                </div>
                <div class="m-field">
                    <label class="m-label">Descripción</label>
                    <textarea id="edit_description" rows="3" class="m-input" placeholder="Descripción del producto..."></textarea>
                </div>
                <div class="m-row">
                    <div>
                        <label class="m-label">SKU *</label>
                        <input type="text" id="edit_sku" class="m-input" placeholder="SKU-001">
                    </div>
                    <div>
                        <label class="m-label">Precio base (Bs.) *</label>
                        <input type="number" id="edit_base_price" class="m-input" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>
                <div class="m-row">
                    <div>
                        <label class="m-label">Stock *</label>
                        <input type="number" id="edit_stock" class="m-input" min="0">
                    </div>
                    <div>
                        <label class="m-label">Días de garantía</label>
                        <input type="number" id="edit_warranty_days" class="m-input" min="0">
                    </div>
                </div>
                <div class="m-row">
                    <div>
                        <label class="m-label">Marca</label>
                        <input type="text" id="edit_brand_name" class="m-input" list="edit-brands-list" placeholder="Selecciona o escribe..." autocomplete="off">
                        <datalist id="edit-brands-list">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->name }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="m-label">Categoría</label>
                        <input type="text" id="edit_category_name" class="m-input" list="edit-categories-list" placeholder="Selecciona o escribe..." autocomplete="off">
                        <datalist id="edit-categories-list">
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="m-field">
                    <label class="m-label">Imágenes actuales</label>
                    <div class="img-preview-grid">
                        @foreach([1,2,3,4] as $i)
                        <div class="img-preview-item">
                            <img id="edit_img_preview_{{ $i }}" src="https://via.placeholder.com/80?text=Sin+img" onerror="this.src='https://via.placeholder.com/80?text=Sin+img'">
                            <p>Imagen {{ $i }}</p>
                            <input type="file" id="edit_file_{{ $i }}" accept="image/jpeg,image/png,image/webp" class="file-input" style="margin-top:4px" onchange="previewModalImg(this, 'edit_img_preview_{{ $i }}')">
                        </div>
                        @endforeach
                    </div>
                    <p style="font-size:11px;color:var(--muted);margin-top:8px"><i class="fa-solid fa-circle-info mr-1"></i>Deja vacío para conservar la imagen actual.</p>
                </div>
            </div>
            <div class="m-footer">
                <button type="button" class="btn-cancel-m" onclick="cerrarModal()">Cancelar</button>
                <button type="button" class="btn-save" id="btnGuardarEdit" onclick="guardarCambios()">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="adminToast" class="fixed bottom-6 right-6 z-[200]"
     style="background:#111f16;border:1px solid rgba(34,197,94,.3);color:#fff;font-size:13px;font-weight:600;padding:12px 18px;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,.5);display:flex;align-items:center;gap:8px;">
    <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
    <span id="adminToastMsg">Acción completada</span>
</div>

<script>
const updateBaseUrl = "{{ url('admin/products') }}";
const csrfToken     = "{{ csrf_token() }}";
let currentProductId = null;

function abrirModalEditar(p) {
    currentProductId = p.id;
    document.getElementById('edit_name').value          = p.name          ?? '';
    document.getElementById('edit_sku').value           = p.sku           ?? '';
    document.getElementById('edit_base_price').value    = p.base_price    ?? '';
    document.getElementById('edit_stock').value         = p.stock         ?? 0;
    document.getElementById('edit_warranty_days').value = p.warranty_days ?? 0;
    document.getElementById('edit_description').value   = p.description   ?? '';
    document.getElementById('edit_brand_name').value    = p.brand_name    ?? '';
    document.getElementById('edit_category_name').value = p.category_name ?? '';
    [1,2,3,4].forEach(i => {
        document.getElementById('edit_file_' + i).value = '';
        const img = document.getElementById('edit_img_preview_' + i);
        const src = p['image' + i];
        img.src = (src && src.length > 0) ? src : 'https://via.placeholder.com/80?text=Sin+img';
    });
    const errDiv = document.getElementById('modalErrorMsg');
    errDiv.style.display = 'none'; errDiv.textContent = '';
    document.getElementById('modalEditProduct').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modalEditProduct').classList.remove('open');
    document.body.style.overflow = '';
}

function cerrarModalEdit(e) {
    if (e.target === document.getElementById('modalEditProduct')) cerrarModal();
}

function previewModalImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById(previewId).src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

async function guardarCambios() {
    if (!currentProductId) return;
    const btn    = document.getElementById('btnGuardarEdit');
    const errDiv = document.getElementById('modalErrorMsg');
    const name   = document.getElementById('edit_name').value.trim();
    const sku    = document.getElementById('edit_sku').value.trim();
    if (!name || !sku) {
        errDiv.textContent = 'El nombre y el SKU son obligatorios.';
        errDiv.style.display = 'block'; return;
    }
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
    errDiv.style.display = 'none';
    const formData = new FormData();
    formData.append('_method',       'PUT');
    formData.append('_token',        csrfToken);
    formData.append('name',          document.getElementById('edit_name').value);
    formData.append('sku',           document.getElementById('edit_sku').value);
    formData.append('base_price',    document.getElementById('edit_base_price').value);
    formData.append('stock',         document.getElementById('edit_stock').value);
    formData.append('warranty_days', document.getElementById('edit_warranty_days').value);
    formData.append('description',   document.getElementById('edit_description').value);
    formData.append('brand_name',    document.getElementById('edit_brand_name').value);
    formData.append('category_name', document.getElementById('edit_category_name').value);
    [1,2,3,4].forEach(i => {
        const f = document.getElementById('edit_file_' + i);
        if (f.files && f.files[0]) formData.append('image' + i, f.files[0]);
    });
    try {
        const response = await fetch(updateBaseUrl + '/' + currentProductId, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        });
        const data = await response.json();
        if (response.ok && (data.success || data.message)) {
            cerrarModal();
            mostrarToast(data.message ?? 'Producto actualizado correctamente');
            setTimeout(() => location.reload(), 800);
        } else {
            let msg = 'Error al guardar los cambios.';
            if (data.errors)       msg = Object.values(data.errors).flat().join(' ');
            else if (data.message) msg = data.message;
            errDiv.textContent   = msg;
            errDiv.style.display = 'block';
        }
    } catch (err) {
        errDiv.textContent   = 'Error de conexión. Intenta nuevamente.';
        errDiv.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Cambios';
    }
}

function mostrarToast(msg) {
    const t = document.getElementById('adminToast');
    document.getElementById('adminToastMsg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

@if(session('success'))
    (function(){ mostrarToast('{{ session('success') }}'); })();
@endif
</script>