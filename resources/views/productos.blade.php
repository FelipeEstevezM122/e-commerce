@extends('layouts.app')

@section('titulo', 'Catálogo de Productos - Casatek')

@section('contenido')

{{-- ===================== ESTILOS ===================== --}}
<style>
    #filterDropdown { display: none; }
    #filterDropdown.open { display: block; }

    #productModal {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
    #productModal.open {
        opacity: 1;
        pointer-events: all;
    }
    #productModal .modal-box {
        transform: scale(0.93);
        transition: transform 0.2s ease;
    }
    #productModal.open .modal-box {
        transform: scale(1);
    }

    #cartToast {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.2s, transform 0.2s;
        pointer-events: none;
    }
    #cartToast.show {
        opacity: 1;
        transform: translateY(0);
    }

    #cartBadge { min-width: 18px; }

    /* Paginador */
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
</style>

<section class="py-6 space-y-8 text-gray-800 dark:text-gray-100">

    {{-- ===================== TOPBAR ===================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-5 border-gray-200 dark:border-gray-700">

        <div class="flex items-center gap-3 shrink-0">
            <span class="w-2 h-6 bg-[#22C55E] rounded-full"></span>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                Nuestro Catálogo de Productos
            </h2>
        </div>

        {{-- Buscador + Filtrar + Carrito --}}
        <form method="GET" action="{{ route('productos') }}" id="filtroForm"
              class="flex items-center gap-3 w-full md:w-auto flex-wrap md:flex-nowrap">

            {{-- Buscador --}}
            <div class="relative flex-1 min-w-[200px]">
                <input
                    id="searchInput"
                    name="q"
                    type="text"
                    value="{{ request('q') }}"
                    placeholder="Buscar cámaras, sensores, alarmas..."
                    class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:border-[#22C55E] focus:outline-none text-sm transition-all shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400">
                <span class="absolute left-4 top-3.5 text-gray-400 dark:text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
            </div>

            {{-- Filtrar --}}
            <div class="relative">
                <button type="button" id="filterBtn"
                        class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-gray-600 hover:border-[#22C55E] transition-all shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-sliders text-sm"></i>
                    Filtrar por
                    <i class="fa-solid fa-chevron-down text-xs transition-transform" id="filterChevron"></i>
                </button>

                <input type="hidden" name="orden" id="ordenInput" value="{{ request('orden', 'default') }}">

                <div id="filterDropdown" class="absolute right-0 top-[calc(100%+8px)] w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl z-50 py-2">
                    <p class="px-4 pt-1 pb-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Ordenar por</p>
                    @foreach([
                        'default'     => ['fa-border-all',       'Por defecto'],
                        'categoria'   => ['fa-folder',           'Categoría'],
                        'marca'       => ['fa-tag',              'Marca'],
                        'nombre'      => ['fa-font',             'Nombre (A–Z)'],
                        'precio_asc'  => ['fa-arrow-up-1-9',     'Precio: menor a mayor'],
                        'precio_desc' => ['fa-arrow-down-9-1',   'Precio: mayor a menor'],
                    ] as $val => [$icon, $label])
                        <button type="button" data-orden="{{ $val }}"
                                class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium
                                       {{ request('orden', 'default') === $val ? 'text-[#22C55E] font-extrabold' : 'text-gray-700 dark:text-gray-200' }}">
                            <i class="fa-solid {{ $icon }} w-4"></i> {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Botón buscar oculto (submit al cambiar orden) --}}
            <button type="submit" class="hidden" id="submitBtn"></button>

        </form>

        {{-- Carrito --}}
        <button id="cartViewBtn"
                class="flex items-center gap-2 px-4 py-2.5 border-2 border-[#22C55E] rounded-xl text-sm font-bold text-[#16a34a] dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-all shadow-sm whitespace-nowrap">
            <i class="fa-solid fa-cart-shopping text-sm"></i>
            Carrito
            <span id="cartBadge" class="bg-[#22C55E] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center h-5 px-1.5">0</span>
        </button>
    </div>

    {{-- ===================== CONTADOR ===================== --}}
    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
        <span>
            Mostrando <strong class="text-gray-800 dark:text-gray-100">{{ $productos->firstItem() ?? 0 }}</strong>
            –
            <strong class="text-gray-800 dark:text-gray-100">{{ $productos->lastItem() ?? 0 }}</strong>
            de
            <strong class="text-gray-800 dark:text-gray-100">{{ $productos->total() }}</strong> productos
        </span>
        @if(request('q'))
            <a href="{{ route('productos') }}" class="text-[#22C55E] hover:underline text-xs font-semibold">
                <i class="fa-solid fa-xmark mr-1"></i>Limpiar búsqueda
            </a>
        @endif
    </div>

    {{-- ===================== GRID ===================== --}}
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($productos as $producto)

            @php
                $imagen = $producto->image1
                    ?? $producto->image2
                    ?? $producto->image3
                    ?? $producto->image4
                    ?? 'https://via.placeholder.com/400x300?text=Sin+imagen';
                $marca  = $producto->brand->name    ?? 'Sin marca';
                $categ  = $producto->category->name ?? 'General';
            @endphp

            <div class="product-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col group cursor-pointer"
                 data-id="{{ $producto->id }}"
                 data-nombre="{{ $producto->name }}"
                 data-marca="{{ $marca }}"
                 data-categoria="{{ $categ }}"
                 data-precio="{{ $producto->base_price }}"
                 data-descripcion="{{ $producto->description ?? 'Sin descripción.' }}"
                 data-img="{{ $imagen }}"
                 data-stock="{{ $producto->stock }}"
                 onclick="abrirModal(this)">

                {{-- Imagen --}}
                <div class="w-full h-48 bg-gray-50 dark:bg-gray-900 overflow-hidden relative border-b border-gray-100 dark:border-gray-700">
                    <img src="{{ $imagen }}" alt="{{ $producto->name }}"
                         class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                         onerror="this.src='https://via.placeholder.com/400x300?text=Sin+imagen'">

                    {{-- Badge marca --}}
                    <span class="absolute top-3 left-3 bg-[#22C55E] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                        {{ $marca }}
                    </span>

                    {{-- Badge stock --}}
                    @if($producto->stock <= 0)
                        <span class="absolute top-3 right-3 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase">
                            Agotado
                        </span>
                    @elseif($producto->stock <= 5)
                        <span class="absolute top-3 right-3 bg-yellow-500 text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase">
                            Últimas unidades
                        </span>
                    @endif
                </div>

                {{-- Info --}}
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
                        <button
                            onclick="agregarAlCarrito(this.closest('.product-card'))"
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

    {{-- ===================== PAGINADOR ===================== --}}
    @if($productos->hasPages())
        <div class="flex items-center justify-center gap-2 pt-4 flex-wrap">

            {{-- Anterior --}}
            @if($productos->onFirstPage())
                <span class="pagination-btn disabled">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $productos->previousPageUrl() }}" class="pagination-btn">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            @endif

            {{-- Números --}}
            @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                @if($page == $productos->currentPage())
                    <span class="pagination-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if($productos->hasMorePages())
                <a href="{{ $productos->nextPageUrl() }}" class="pagination-btn">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="pagination-btn disabled">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            @endif

        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500">
            Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
        </p>
    @endif

</section>

{{-- ===================== MODAL ===================== --}}
<div id="productModal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
     onclick="cerrarModal(event)">
    <div class="modal-box bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-2xl w-full max-w-md relative">

        <button onclick="cerrarModal(null)"
                class="absolute top-3 right-3 z-10 bg-black/40 hover:bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="w-full h-56 bg-gray-100 dark:bg-gray-900 overflow-hidden">
            <img id="modalImg" src="" alt="" class="w-full h-full object-contain p-4">
        </div>

        <div class="p-5 space-y-3">
            <p id="modalMarca"  class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider"></p>
            <p id="modalCateg"  class="text-[10px] text-gray-400 uppercase tracking-wider"></p>
            <h3 id="modalNombre" class="text-xl font-black text-gray-900 dark:text-white"></h3>
            <p id="modalDesc"   class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed"></p>
            <p id="modalPrecio" class="text-2xl font-black text-gray-900 dark:text-green-400"></p>

            <div class="space-y-2 pt-1">
                <button id="modalCartBtn"
                        class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i>
                    Agregar al carrito
                </button>
                <a id="modalWaBtn" href="#" target="_blank"
                   class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                    Cotizar por WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== TOAST ===================== --}}
<div id="cartToast"
     class="fixed bottom-6 right-6 z-[200] bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-xl flex items-center gap-2">
    <i class="fa-solid fa-circle-check text-[#22C55E]"></i>
    <span id="cartToastMsg">Producto agregado</span>
</div>

{{-- ===================== SCRIPTS ===================== --}}
<script>
    let carrito = [];

    // ── Badge ──
    function actualizarBadge() {
        document.getElementById('cartBadge').textContent = carrito.length;
    }

    // ── Toast ──
    function mostrarToast(nombre) {
        document.getElementById('cartToastMsg').textContent = '"' + nombre + '" agregado al carrito';
        const t = document.getElementById('cartToast');
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(() => t.classList.remove('show'), 2400);
    }

    // ── Agregar al carrito ──
    function agregarAlCarrito(card) {
        const item = {
            id:          card.dataset.id,
            nombre:      card.dataset.nombre,
            marca:       card.dataset.marca,
            precio:      card.dataset.precio,
            descripcion: card.dataset.descripcion,
            img:         card.dataset.img,
        };
        carrito.push(item);
        actualizarBadge();
        mostrarToast(item.nombre);
    }

    // ── Ver carrito ──
    document.getElementById('cartViewBtn').addEventListener('click', () => {
        if (carrito.length === 0) {
            alert('🛒 Tu carrito está vacío.');
            return;
        }
        const resumen = carrito.reduce((acc, p) => {
            acc[p.nombre] = (acc[p.nombre] || 0) + 1;
            return acc;
        }, {});
        const texto = Object.entries(resumen)
            .map(([n, c]) => '• ' + n + (c > 1 ? ' x' + c : '') )
            .join('\n');
        alert('🛒 Carrito (' + carrito.length + ' item' + (carrito.length > 1 ? 's' : '') + '):\n\n' + texto);
    });

    // ── Modal ──
    let productoModal = null;

    function abrirModal(card) {
        productoModal = {
            id:          card.dataset.id,
            nombre:      card.dataset.nombre,
            marca:       card.dataset.marca,
            categoria:   card.dataset.categoria,
            precio:      card.dataset.precio,
            descripcion: card.dataset.descripcion,
            img:         card.dataset.img,
        };
        document.getElementById('modalImg').src            = productoModal.img;
        document.getElementById('modalMarca').textContent  = productoModal.marca;
        document.getElementById('modalCateg').textContent  = productoModal.categoria;
        document.getElementById('modalNombre').textContent = productoModal.nombre;
        document.getElementById('modalDesc').textContent   = productoModal.descripcion;
        document.getElementById('modalPrecio').textContent = parseFloat(productoModal.precio).toFixed(2) + ' Bs.';
        document.getElementById('modalWaBtn').href =
            'https://wa.me/59176216837?text=Hola,%20me%20interesa%20el%20producto:%20' + encodeURIComponent(productoModal.nombre);
        document.getElementById('productModal').classList.add('open');
    }

    function cerrarModal(event) {
        if (event === null || event.target === document.getElementById('productModal')) {
            document.getElementById('productModal').classList.remove('open');
        }
    }

    document.getElementById('modalCartBtn').addEventListener('click', () => {
        if (productoModal) {
            carrito.push({ ...productoModal });
            actualizarBadge();
            mostrarToast(productoModal.nombre);
            document.getElementById('productModal').classList.remove('open');
        }
    });

    // ── Filtros ──
    document.getElementById('filterBtn').addEventListener('click', (e) => {
        e.stopPropagation();
        const dd = document.getElementById('filterDropdown');
        dd.classList.toggle('open');
        document.getElementById('filterChevron').style.transform =
            dd.classList.contains('open') ? 'rotate(180deg)' : '';
    });

    document.addEventListener('click', () => {
        document.getElementById('filterDropdown').classList.remove('open');
        document.getElementById('filterChevron').style.transform = '';
    });

    document.querySelectorAll('.filter-opt').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.getElementById('ordenInput').value = btn.dataset.orden;
            document.getElementById('submitBtn').click();
        });
    });

    // ── Búsqueda con Enter ──
    document.getElementById('searchInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('submitBtn').click();
        }
    });
</script>

@endsection