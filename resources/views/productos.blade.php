@extends('layouts.app')

@section('titulo', 'Catálogo de Productos - Casatek')

@section('contenido')

<style>
    #filterDropdown { display: none; }
    #filterDropdown.open { display: block; }

    /* ── MODAL ── */
    #productModal {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    #productModal.open {
        opacity: 1;
        pointer-events: all;
    }
    #productModal .modal-box {
        transform: scale(0.93) translateY(10px);
        transition: transform 0.25s ease;
    }
    #productModal.open .modal-box {
        transform: scale(1) translateY(0);
    }

    /* Galería de imágenes en el modal */
    .modal-thumb {
        width: 60px; height: 60px;
        object-fit: contain;
        border-radius: 10px;
        border: 2px solid transparent;
        background: #f3f4f6;
        cursor: pointer;
        transition: border-color .15s, transform .15s;
        padding: 4px;
    }
    .dark .modal-thumb { background: #1f2937; }
    .modal-thumb:hover  { transform: scale(1.05); }
    .modal-thumb.active { border-color: #22C55E; }

    /* Badge de stock */
    .stock-ok   { background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
    .stock-low  { background:#fef9c3; color:#92400e; border:1px solid #fde68a; }
    .stock-none { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
    .dark .stock-ok   { background:rgba(34,197,94,.15);  color:#4ade80; border-color:rgba(34,197,94,.3); }
    .dark .stock-low  { background:rgba(250,204,21,.15); color:#facc15; border-color:rgba(250,204,21,.3); }
    .dark .stock-none { background:rgba(239,68,68,.15);  color:#f87171; border-color:rgba(239,68,68,.3); }

    /* Pill info */
    .info-pill {
        display:inline-flex; align-items:center; gap:5px;
        padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700;
        background:rgba(0,0,0,.04); border:1px solid rgba(0,0,0,.08);
        color:#374151;
    }
    .dark .info-pill { background:rgba(255,255,255,.06); border-color:rgba(255,255,255,.1); color:#d1d5db; }

    /* Toast */
    #cartToast {
        opacity: 0; transform: translateY(8px);
        transition: opacity 0.2s, transform 0.2s;
        pointer-events: none;
    }
    #cartToast.show { opacity: 1; transform: translateY(0); }
    #cartBadge { min-width: 18px; }

    .pagination-btn {
        @apply px-3 py-1.5 rounded-lg text-sm font-semibold border transition-all;
    }
    .pagination-btn.active {
        @apply bg-[#22C55E] text-white border-[#22C55E];
    }
    .pagination-btn:not(.active) {
        @apply border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-green-50 hover:border-[#22C55E];
    }
    .pagination-btn.disabled {
        @apply opacity-40 pointer-events-none;
    }

    /* Scroll suave en modal */
    .modal-scroll { overflow-y: auto; max-height: 85vh; }
    .modal-scroll::-webkit-scrollbar { width: 4px; }
    .modal-scroll::-webkit-scrollbar-track { background: transparent; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #22C55E44; border-radius: 4px; }
</style>

<section class="py-6 space-y-8 text-gray-800 dark:text-gray-100">

    {{-- TOPBAR --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-5 border-gray-200 dark:border-gray-700">

        <div class="flex items-center gap-3 shrink-0">
            <span class="w-2 h-6 bg-[#22C55E] rounded-full"></span>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                Nuestro Catálogo de Productos
            </h2>
        </div>

        <form method="GET" action="{{ route('productos') }}" id="filtroForm"
              class="flex items-center gap-2 w-full md:w-auto flex-wrap">

            <div class="relative flex-1 min-w-[180px]">
                <input id="searchInput" name="q" type="text" value="{{ request('q') }}"
                       placeholder="Buscar cámaras, sensores, alarmas..."
                       class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:border-[#22C55E] focus:outline-none text-sm transition-all shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400">
                <span class="absolute left-4 top-3.5 text-gray-400 dark:text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
            </div>

            <select name="category_id" onchange="document.getElementById('filtroForm').submit()"
                    class="py-2.5 pl-3 pr-8 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#22C55E] focus:outline-none transition-all shadow-sm min-w-[150px]">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="brand_id" onchange="document.getElementById('filtroForm').submit()"
                    class="py-2.5 pl-3 pr-8 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#22C55E] focus:outline-none transition-all shadow-sm min-w-[130px]">
                <option value="">Todas las marcas</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>

            <div class="relative">
                <button type="button" id="filterBtn"
                        class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-gray-600 hover:border-[#22C55E] transition-all shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-arrow-up-wide-short text-sm"></i>
                    Ordenar
                    <i class="fa-solid fa-chevron-down text-xs transition-transform" id="filterChevron"></i>
                </button>
                <input type="hidden" name="orden" id="ordenInput" value="{{ request('orden', 'default') }}">
                <div id="filterDropdown" class="absolute right-0 top-[calc(100%+8px)] w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl z-50 py-2">
                    <p class="px-4 pt-1 pb-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Ordenar por</p>
                    @foreach([
                        'default'     => ['fa-border-all',     'Por defecto'],
                        'nombre'      => ['fa-font',           'Nombre (A–Z)'],
                        'precio_asc'  => ['fa-arrow-up-1-9',   'Precio: menor a mayor'],
                        'precio_desc' => ['fa-arrow-down-9-1', 'Precio: mayor a menor'],
                        'marca'       => ['fa-tag',            'Marca (A–Z)'],
                        'categoria'   => ['fa-folder',         'Categoría (A–Z)'],
                    ] as $val => [$icon, $label])
                        <button type="button" data-orden="{{ $val }}"
                                class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium {{ request('orden','default') === $val ? 'text-[#22C55E] font-extrabold' : 'text-gray-700 dark:text-gray-200' }}">
                            <i class="fa-solid {{ $icon }} w-4"></i> {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2.5 bg-[#22C55E] hover:bg-green-600 text-white font-bold rounded-xl text-sm transition-all shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-magnifying-glass text-sm"></i> Buscar
            </button>

            @if(request('q') || request('category_id') || request('brand_id') || (request('orden') !== null && request('orden') !== 'default'))
                <a href="{{ route('productos') }}"
                   class="flex items-center gap-2 px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-500 dark:text-gray-400 hover:border-red-400 hover:text-red-400 transition-all shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-xmark text-sm"></i> Limpiar
                </a>
            @endif

            <button type="submit" class="hidden" id="submitBtn"></button>
        </form>

        <button id="cartViewBtn"
                class="flex items-center gap-2 px-4 py-2.5 border-2 border-[#22C55E] rounded-xl text-sm font-bold text-[#16a34a] dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-all shadow-sm whitespace-nowrap">
            <i class="fa-solid fa-cart-shopping text-sm"></i>
            Carrito
            <span id="cartBadge" class="bg-[#22C55E] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center h-5 px-1.5">0</span>
        </button>
    </div>

    {{-- CONTADOR --}}
    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 flex-wrap gap-2">
        <span>
            Mostrando <strong class="text-gray-800 dark:text-gray-100">{{ $productos->firstItem() ?? 0 }}</strong>
            – <strong class="text-gray-800 dark:text-gray-100">{{ $productos->lastItem() ?? 0 }}</strong>
            de <strong class="text-gray-800 dark:text-gray-100">{{ $productos->total() }}</strong> productos
        </span>
        @if(request('q') || request('category_id') || request('brand_id'))
            <div class="flex items-center gap-2 flex-wrap">
                @if(request('q'))
                    <span class="inline-flex items-center gap-1.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i> "{{ request('q') }}"
                    </span>
                @endif
                @if(request('category_id'))
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="fa-solid fa-folder text-[10px]"></i>
                        {{ $categories->firstWhere('id', request('category_id'))->name ?? 'Categoría' }}
                    </span>
                @endif
                @if(request('brand_id'))
                    <span class="inline-flex items-center gap-1.5 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="fa-solid fa-tag text-[10px]"></i>
                        {{ $brands->firstWhere('id', request('brand_id'))->name ?? 'Marca' }}
                    </span>
                @endif
                <a href="{{ route('productos') }}" class="text-red-400 hover:text-red-500 text-xs font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Limpiar filtros
                </a>
            </div>
        @endif
    </div>

    {{-- GRID DE PRODUCTOS --}}
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($productos as $producto)
        @php
            $imagen  = $producto->image1 ?? $producto->image2 ?? $producto->image3 ?? 'https://via.placeholder.com/400x300?text=Sin+imagen';
            $marca   = $producto->brand->name    ?? 'Sin marca';
            $categ   = $producto->category->name ?? 'General';
            $imgs    = array_filter([$producto->image1, $producto->image2, $producto->image3]);
        @endphp

        <div class="product-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col group cursor-pointer"
             data-id="{{ $producto->id }}"
             data-nombre="{{ $producto->name }}"
             data-marca="{{ $marca }}"
             data-categoria="{{ $categ }}"
             data-precio="{{ $producto->base_price }}"
             data-descripcion="{{ $producto->description ?? 'Sin descripción.' }}"
             data-img="{{ $imagen }}"
             data-img1="{{ $producto->image1 ?? '' }}"
             data-img2="{{ $producto->image2 ?? '' }}"
             data-img3="{{ $producto->image3 ?? '' }}"
             data-stock="{{ $producto->stock }}"
             data-sku="{{ $producto->sku }}"
             data-garantia="{{ $producto->warranty_days ?? 0 }}"
             onclick="abrirModal(this)">

            <div class="w-full h-48 bg-gray-50 dark:bg-gray-900 overflow-hidden relative border-b border-gray-100 dark:border-gray-700">
                <img src="{{ $imagen }}" alt="{{ $producto->name }}"
                     class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://via.placeholder.com/400x300?text=Sin+imagen'">
                <span class="absolute top-3 left-3 bg-[#22C55E] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                    {{ $marca }}
                </span>
                @if($producto->stock <= 0)
                    <span class="absolute top-3 right-3 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase">Agotado</span>
                @elseif($producto->stock <= 5)
                    <span class="absolute top-3 right-3 bg-yellow-500 text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase">Últimas unidades</span>
                @endif
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">{{ $categ }}</span>
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm line-clamp-1 group-hover:text-[#22C55E] transition-colors">
                        {{ $producto->name }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                        {{ $producto->description ?? 'Sin descripción.' }}
                    </p>
                    <div class="pt-1">
                        <span class="text-lg font-black text-gray-900 dark:text-green-400">
                            {{ number_format($producto->base_price, 2) }} Bs.
                        </span>
                    </div>
                </div>

                <div class="space-y-2" onclick="event.stopPropagation()">
                    <button onclick="agregarAlCarrito(this.closest('.product-card'))"
                            {{ $producto->stock <= 0 ? 'disabled' : '' }}
                            class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-xs font-bold py-2 rounded-xl transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-cart-plus text-sm"></i>
                        {{ $producto->stock <= 0 ? 'Sin stock' : 'Agregar al carrito' }}
                    </button>
                    <a href="https://wa.me/59176216837?text=Hola,%20me%20interesa%20el%20producto:%20{{ urlencode($producto->name) }}"
                       target="_blank"
                       class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        Cotizar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-20 text-gray-400 dark:text-gray-500">
            <i class="fa-solid fa-box-open text-5xl mb-4"></i>
            <p class="text-lg font-semibold">No se encontraron productos</p>
            <p class="text-sm mt-1">Intenta con otra búsqueda o filtro</p>
            <a href="{{ route('productos') }}" class="mt-4 inline-block text-[#22C55E] hover:underline text-sm font-bold">Ver todos los productos</a>
        </div>
        @endforelse
    </div>

    {{-- PAGINADOR --}}
    @if($productos->hasPages())
        <div class="flex items-center justify-center gap-2 pt-4 flex-wrap">
            @if($productos->onFirstPage())
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-left text-xs"></i></span>
            @else
                <a href="{{ $productos->previousPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-left text-xs"></i></a>
            @endif
            @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                @if($page == $productos->currentPage())
                    <span class="pagination-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endforeach
            @if($productos->hasMorePages())
                <a href="{{ $productos->nextPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-right text-xs"></i></a>
            @else
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-right text-xs"></i></span>
            @endif
        </div>
        <p class="text-center text-xs text-gray-400 dark:text-gray-500">
            Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
        </p>
    @endif

</section>

{{-- ══════════════ MODAL DETALLE COMPLETO ══════════════ --}}
<div id="productModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     onclick="cerrarModal(event)">

    <div class="modal-box modal-scroll bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative">

        {{-- Botón cerrar --}}
        <button onclick="cerrarModal(null)"
                class="absolute top-3 right-3 z-20 bg-black/30 hover:bg-black/50 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        {{-- ── IMAGEN PRINCIPAL ── --}}
        <div class="w-full h-64 bg-gray-100 dark:bg-gray-800 overflow-hidden relative rounded-t-2xl">
            <img id="modalImgMain" src="" alt=""
                 class="w-full h-full object-contain p-6 transition-opacity duration-200">
            {{-- Badge stock sobre imagen --}}
            <span id="modalStockBadge"
                  class="absolute bottom-3 right-3 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider"></span>
        </div>

        {{-- ── GALERÍA MINIATURAS ── --}}
        <div id="modalThumbs" class="flex gap-2 px-5 pt-3 pb-1"></div>

        {{-- ── CONTENIDO ── --}}
        <div class="px-5 pb-6 pt-3 space-y-4">

            {{-- Marca + Categoría --}}
            <div class="flex items-center gap-2 flex-wrap">
                <span id="modalMarca"
                      class="inline-flex items-center gap-1.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    <i class="fa-solid fa-tag text-[9px]"></i>
                </span>
                <span id="modalCateg"
                      class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    <i class="fa-solid fa-folder text-[9px]"></i>
                </span>
            </div>

            {{-- Nombre --}}
            <h3 id="modalNombre" class="text-xl font-black text-gray-900 dark:text-white leading-tight"></h3>

            {{-- Precio --}}
            <p id="modalPrecio" class="text-3xl font-black text-[#22C55E]"></p>

            {{-- Pills: SKU + Garantía + Stock --}}
            <div class="flex flex-wrap gap-2">
                <span class="info-pill">
                    <i class="fa-solid fa-barcode text-[10px] text-gray-400"></i>
                    <span class="text-gray-400 font-normal">SKU:</span>
                    <span id="modalSku" class="font-mono"></span>
                </span>
                <span class="info-pill" id="modalGarantiaPill">
                    <i class="fa-solid fa-shield-halved text-[10px] text-blue-400"></i>
                    <span id="modalGarantia"></span>
                </span>
                <span class="info-pill" id="modalStockPill">
                    <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                    <span id="modalStockText"></span>
                </span>
            </div>

            {{-- Separador --}}
            <hr class="border-gray-100 dark:border-gray-700">

            {{-- Descripción --}}
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Descripción</p>
                <p id="modalDesc" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed"></p>
            </div>

            {{-- Separador --}}
            <hr class="border-gray-100 dark:border-gray-700">

            {{-- Botones --}}
            <div class="space-y-2 pt-1">
                <button id="modalCartBtn"
                        class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-sm font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i>
                    Agregar al carrito
                </button>
                <a id="modalWaBtn" href="#" target="_blank"
                   class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-sm font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                    Cotizar por WhatsApp
                </a>
            </div>

        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="cartToast"
     class="fixed bottom-6 right-6 z-[200] bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-xl flex items-center gap-2">
    <i id="cartToastIcon" class="fa-solid fa-circle-check text-[#22C55E]"></i>
    <span id="cartToastMsg">Producto agregado</span>
</div>

{{-- ══════════════ SCRIPTS ══════════════ --}}
<script>
const CART_KEY = 'casatek_carrito';

function getToken()    { return localStorage.getItem('token') || null; }
function getCarrito()  { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCarrito(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }

function actualizarBadge() {
    const total = getCarrito().reduce((acc, i) => acc + i.cantidad, 0);
    document.getElementById('cartBadge').textContent = total;
}

function mostrarToast(nombre) {
    const t = document.getElementById('cartToast');
    document.getElementById('cartToastMsg').textContent = '"' + nombre + '" agregado al carrito';
    document.getElementById('cartToastIcon').className = 'fa-solid fa-circle-check text-[#22C55E]';
    t.style.background = '';
    t.classList.add('show');
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.remove('show'), 2400);
}

function mostrarToastError(msg) {
    const t = document.getElementById('cartToast');
    document.getElementById('cartToastMsg').textContent = msg;
    document.getElementById('cartToastIcon').className = 'fa-solid fa-circle-xmark text-red-400';
    t.style.background = '#7f1d1d';
    t.classList.add('show');
    clearTimeout(t._timer);
    t._timer = setTimeout(() => { t.classList.remove('show'); t.style.background = ''; }, 2400);
}

async function agregarAlCarrito(card) {
    const stock = parseInt(card.dataset.stock ?? '0');
    if (stock <= 0) { mostrarToastError('Este producto no tiene stock disponible'); return; }

    const id       = String(card.dataset.id);
    const nombre   = card.dataset.nombre;
    const precio   = parseFloat(card.dataset.precio);
    const carrito  = getCarrito();
    const existe   = carrito.find(i => i.id === id);
    const yaEnCar  = existe ? existe.cantidad : 0;

    if (yaEnCar >= stock) {
        mostrarToastError('Ya tienes el máximo disponible (' + stock + ' unid.)');
        return;
    }

    if (existe) {
        existe.cantidad++;
    } else {
        carrito.push({
            id, nombre,
            marca:       card.dataset.marca,
            precio,
            descripcion: card.dataset.descripcion,
            img:         card.dataset.img,
            cantidad:    1,
        });
    }
    saveCarrito(carrito);
    actualizarBadge();
    mostrarToast(nombre);

    const token = getToken();
    if (!token) return;
    try {
        const res = await fetch('/api/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify({ product_id: parseInt(id), quantity: 1 }),
        });
        if (!res.ok) {
            const data = await res.json();
            const c    = getCarrito();
            const item = c.find(i => i.id === id);
            if (item) { item.cantidad--; if (item.cantidad <= 0) c.splice(c.indexOf(item), 1); saveCarrito(c); actualizarBadge(); }
            mostrarToastError(data.message || 'Error al agregar al carrito');
        }
    } catch(e) { console.warn('No se pudo sincronizar:', e); }
}

document.getElementById('cartViewBtn').addEventListener('click', () => { window.location.href = '/carrito'; });

// ── MODAL ──
let productoModal = null;

function setMainImg(src) {
    const img = document.getElementById('modalImgMain');
    img.style.opacity = '0';
    setTimeout(() => { img.src = src; img.style.opacity = '1'; }, 150);

    // Marcar thumb activo
    document.querySelectorAll('.modal-thumb').forEach(t => {
        t.classList.toggle('active', t.dataset.src === src);
    });
}

function abrirModal(card) {
    const d = card.dataset;
    productoModal = {
        id:          String(d.id),
        nombre:      d.nombre,
        marca:       d.marca,
        categoria:   d.categoria,
        precio:      parseFloat(d.precio),
        descripcion: d.descripcion,
        img:         d.img,
        img1:        d.img1,
        img2:        d.img2,
        img3:        d.img3,
        stock:       parseInt(d.stock ?? '0'),
        sku:         d.sku,
        garantia:    parseInt(d.garantia ?? '0'),
    };

    // Imagen principal
    setMainImg(productoModal.img1 || productoModal.img);

    // Galería de miniaturas
    const thumbsEl = document.getElementById('modalThumbs');
    thumbsEl.innerHTML = '';
    const imgs = [productoModal.img1, productoModal.img2, productoModal.img3].filter(Boolean);
    if (imgs.length > 1) {
        imgs.forEach((src, i) => {
            const img = document.createElement('img');
            img.src          = src;
            img.dataset.src  = src;
            img.className    = 'modal-thumb' + (i === 0 ? ' active' : '');
            img.alt          = 'Imagen ' + (i + 1);
            img.onclick      = () => setMainImg(src);
            img.onerror      = () => img.style.display = 'none';
            thumbsEl.appendChild(img);
        });
    }

    // Textos
    document.getElementById('modalMarca').innerHTML  = `<i class="fa-solid fa-tag text-[9px]"></i> ${productoModal.marca}`;
    document.getElementById('modalCateg').innerHTML  = `<i class="fa-solid fa-folder text-[9px]"></i> ${productoModal.categoria}`;
    document.getElementById('modalNombre').textContent = productoModal.nombre;
    document.getElementById('modalPrecio').textContent = productoModal.precio.toFixed(2) + ' Bs.';
    document.getElementById('modalDesc').textContent   = productoModal.descripcion || 'Sin descripción.';
    document.getElementById('modalSku').textContent    = productoModal.sku || '—';

    // Garantía
    const g = productoModal.garantia;
    let garantiaText = 'Sin garantía';
    if (g > 0) {
        if (g % 365 === 0)      garantiaText = (g / 365) + ' año' + (g / 365 > 1 ? 's' : '');
        else if (g % 30 === 0)  garantiaText = (g / 30)  + ' mes' + (g / 30  > 1 ? 'es' : '');
        else                    garantiaText = g + ' días';
    }
    document.getElementById('modalGarantia').textContent = '🛡 ' + garantiaText;

    // Stock badge + pill
    const s = productoModal.stock;
    const badge  = document.getElementById('modalStockBadge');
    const pill   = document.getElementById('modalStockPill');
    const sText  = document.getElementById('modalStockText');

    if (s <= 0) {
        badge.textContent = 'Sin stock';
        badge.className   = 'absolute bottom-3 right-3 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider stock-none';
        pill.className    = 'info-pill stock-none';
        sText.textContent = '📦 Sin stock';
    } else if (s <= 5) {
        badge.textContent = 'Últimas ' + s + ' unidades';
        badge.className   = 'absolute bottom-3 right-3 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider stock-low';
        pill.className    = 'info-pill stock-low';
        sText.textContent = '📦 ' + s + ' en stock';
    } else {
        badge.textContent = s + ' en stock';
        badge.className   = 'absolute bottom-3 right-3 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider stock-ok';
        pill.className    = 'info-pill stock-ok';
        sText.textContent = '📦 ' + s + ' disponibles';
    }

    // WhatsApp
    document.getElementById('modalWaBtn').href =
        'https://wa.me/59176216837?text=Hola,%20me%20interesa%20el%20producto:%20' + encodeURIComponent(productoModal.nombre);

    // Botón carrito
    const btn = document.getElementById('modalCartBtn');
    if (s <= 0) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-ban"></i> Sin stock';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Agregar al carrito';
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    document.getElementById('productModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarModal(event) {
    if (event === null || event.target === document.getElementById('productModal')) {
        document.getElementById('productModal').classList.remove('open');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(null); });

document.getElementById('modalCartBtn').addEventListener('click', async () => {
    if (!productoModal) return;
    const fakeCard = { dataset: {
        id: productoModal.id, nombre: productoModal.nombre, marca: productoModal.marca,
        precio: productoModal.precio, descripcion: productoModal.descripcion,
        img: productoModal.img, stock: productoModal.stock,
    }};
    await agregarAlCarrito(fakeCard);
    if (productoModal.stock > 0) cerrarModal(null);
});

// ── Filtros ──
document.getElementById('filterBtn').addEventListener('click', e => {
    e.stopPropagation();
    const dd = document.getElementById('filterDropdown');
    dd.classList.toggle('open');
    document.getElementById('filterChevron').style.transform = dd.classList.contains('open') ? 'rotate(180deg)' : '';
});
document.addEventListener('click', () => {
    document.getElementById('filterDropdown').classList.remove('open');
    document.getElementById('filterChevron').style.transform = '';
});
document.querySelectorAll('.filter-opt').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        document.getElementById('ordenInput').value = btn.dataset.orden;
        document.getElementById('submitBtn').click();
    });
});
document.getElementById('searchInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('submitBtn').click(); }
});

actualizarBadge();
</script>

@endsection