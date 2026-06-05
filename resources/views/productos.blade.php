@extends('layouts.app')
 
@section('titulo', 'Catálogo de Productos - Casatek')
 
@section('contenido')
@php
$productosDemos = [
    ['marca' => 'HIKVISION', 'nombre' => 'Cámara IP Full HD',     'descripcion' => 'Cámara de vigilancia de alta definición para interiores y exteriores.', 'precio' => 450,  'categoria' => 'Cámaras',  'img' => 'https://via.placeholder.com/400x300?text=Camara+IP'],
    ['marca' => 'DAHUA',     'nombre' => 'Kit de Seguridad CCTV', 'descripcion' => 'Sistema completo de monitoreo para hogares y empresas.',                  'precio' => 1200, 'categoria' => 'Sistemas',  'img' => 'https://via.placeholder.com/400x300?text=CCTV'],
    ['marca' => 'AJAX',      'nombre' => 'Sensor de Movimiento',  'descripcion' => 'Detector inteligente con tecnología inalámbrica.',                        'precio' => 180,  'categoria' => 'Sensores',  'img' => 'https://via.placeholder.com/400x300?text=Sensor'],
    ['marca' => 'TP-LINK',   'nombre' => 'Router Empresarial',    'descripcion' => 'Router de alto rendimiento para redes empresariales.',                     'precio' => 650,  'categoria' => 'Redes',     'img' => 'https://via.placeholder.com/400x300?text=Router'],
    ['marca' => 'AJAX',      'nombre' => 'Sensor de Movimiento',  'descripcion' => 'Detector inteligente con tecnología inalámbrica.',                        'precio' => 180,  'categoria' => 'Sensores',  'img' => 'https://via.placeholder.com/400x300?text=Sensor'],
    ['marca' => 'TP-LINK',   'nombre' => 'Router Empresarial',    'descripcion' => 'Router de alto rendimiento para redes empresariales.',                     'precio' => 650,  'categoria' => 'Redes',     'img' => 'https://via.placeholder.com/400x300?text=Router'],
    ['marca' => 'AJAX',      'nombre' => 'Sensor de Movimiento',  'descripcion' => 'Detector inteligente con tecnología inalámbrica.',                        'precio' => 180,  'categoria' => 'Sensores',  'img' => 'https://via.placeholder.com/400x300?text=Sensor'],
    ['marca' => 'TP-LINK',   'nombre' => 'Router Empresarial',    'descripcion' => 'Router de alto rendimiento para redes empresariales.',                     'precio' => 650,  'categoria' => 'Redes',     'img' => 'https://via.placeholder.com/400x300?text=Router'],
];
@endphp
 
{{-- ===================== ESTILOS ===================== --}}
<style>
    /* ---- Filtro dropdown ---- */
    #filterDropdown { display: none; }
    #filterDropdown.open { display: block; }
 
    /* ---- Modal ---- */
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
 
    /* ---- Toast ---- */
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
 
    /* ---- Badge de cantidad ---- */
    #cartBadge { min-width: 18px; }
</style>
 
<section class="py-6 space-y-8 text-gray-800 dark:text-gray-100">
 
    {{-- ===================== TOPBAR ===================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-5 border-gray-200 dark:border-gray-700">
 
        {{-- Título --}}
        <div class="flex items-center gap-3 shrink-0">
            <span class="w-2 h-6 bg-[#22C55E] rounded-full"></span>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                Nuestro Catálogo de Productos
            </h2>
        </div>
 
        {{-- Buscador + Filtrar + Carrito --}}
        <div class="flex items-center gap-3 w-full md:w-auto flex-wrap md:flex-nowrap">
 
            {{-- Buscador --}}
            <div class="relative flex-1 min-w-[200px]">
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Buscar cámaras, sensores, alarmas..."
                    class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:border-[#22C55E] focus:outline-none text-sm transition-all shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400">
                <span class="absolute left-4 top-3.5 text-gray-400 dark:text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
            </div>
 
            {{-- Botón Filtrar --}}
            <div class="relative">
                <button
                    id="filterBtn"
                    class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-gray-600 hover:border-[#22C55E] transition-all shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-sliders text-sm"></i>
                    Filtrar por
                    <i class="fa-solid fa-chevron-down text-xs transition-transform" id="filterChevron"></i>
                </button>
 
                {{-- Dropdown de filtros --}}
                <div id="filterDropdown" class="absolute right-0 top-[calc(100%+8px)] w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl z-50 py-2">
                    <p class="px-4 pt-1 pb-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Ordenar por</p>
                    <button data-filter="default"      class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium active-opt"><i class="fa-solid fa-border-all w-4"></i> Por defecto</button>
                    <button data-filter="categoria"    class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium"><i class="fa-solid fa-folder w-4"></i> Categoría</button>
                    <button data-filter="marca"        class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium"><i class="fa-solid fa-tag w-4"></i> Marca</button>
                    <button data-filter="nombre"       class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium"><i class="fa-solid fa-font w-4"></i> Nombre (A–Z)</button>
                    <button data-filter="precio_asc"   class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium"><i class="fa-solid fa-arrow-up-1-9 w-4"></i> Precio: menor a mayor</button>
                    <button data-filter="precio_desc"  class="filter-opt w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-[#22C55E] transition-colors text-left font-medium"><i class="fa-solid fa-arrow-down-9-1 w-4"></i> Precio: mayor a menor</button>
                </div>
            </div>
 
            {{-- Botón Carrito --}}
            <button
                id="cartViewBtn"
                class="flex items-center gap-2 px-4 py-2.5 border-2 border-[#22C55E] rounded-xl text-sm font-bold text-[#16a34a] dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-all shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-cart-shopping text-sm"></i>
                Carrito
                <span id="cartBadge" class="bg-[#22C55E] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center h-5 px-1.5">0</span>
            </button>
 
        </div>
    </div>
 
    {{-- ===================== GRID DE PRODUCTOS ===================== --}}
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
 
        @forelse($images ?? [] as $image)
 
            <div class="product-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col group cursor-pointer"
                 data-nombre="{{ $image->original_name }}"
                 data-marca="IMPORTADO"
                 data-categoria="General"
                 data-precio="0"
                 data-descripcion="Descripción del componente importado."
                 data-img="{{ $image->cloudinary_url }}"
                 onclick="abrirModal(this)">
 
                <div class="w-full h-48 bg-gray-50 dark:bg-gray-900 overflow-hidden relative border-b border-gray-100 dark:border-gray-700">
                    <img src="{{ $image->cloudinary_url }}" alt="{{ $image->original_name }}"
                         class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 right-3 bg-gray-900/80 dark:bg-gray-700/90 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                        {{ $image->format }}
                    </span>
                </div>
 
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div class="space-y-1">
                        <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">MARCA DISPOSITIVO</span>
                        <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm line-clamp-1 group-hover:text-[#22C55E] transition-colors">{{ $image->original_name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">Descripción del componente importado.</p>
                        <div class="pt-1">
                            <span class="text-lg font-black text-gray-900 dark:text-green-400">0.00 Bs.</span>
                        </div>
                    </div>
 
                    <div class="space-y-2" onclick="event.stopPropagation()">
                        {{-- Agregar al carrito --}}
                        <button onclick="agregarAlCarrito(this.closest('.product-card'))"
                                class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-xs font-bold py-2 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus text-sm"></i>
                            Agregar al carrito
                        </button>
                        <a href="https://wa.me/59176216837" target="_blank"
                           class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            Cotizar por WhatsApp
                        </a>
                    </div>
                </div>
 
            </div>
 
        @empty
 
            @foreach($productosDemos as $prod)
 
                <div class="product-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group cursor-pointer"
                     data-nombre="{{ $prod['nombre'] }}"
                     data-marca="{{ $prod['marca'] }}"
                     data-categoria="{{ $prod['categoria'] ?? 'General' }}"
                     data-precio="{{ $prod['precio'] }}"
                     data-descripcion="{{ $prod['descripcion'] }}"
                     data-img="{{ $prod['img'] }}"
                     onclick="abrirModal(this)">
 
                    <div class="w-full h-48 bg-gray-50 dark:bg-gray-900 overflow-hidden relative">
                        <img src="{{ $prod['img'] }}" alt="Demo Casatek"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 right-3 bg-[#22C55E] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                            Demo
                        </span>
                    </div>
 
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1">
                            <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">{{ $prod['marca'] }}</span>
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm line-clamp-1 group-hover:text-[#22C55E] transition-colors">{{ $prod['nombre'] }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $prod['descripcion'] }}</p>
                            <div class="pt-1">
                                <span class="text-lg font-black text-gray-900 dark:text-white">{{ $prod['precio'] }} Bs.</span>
                            </div>
                        </div>
 
                        <div class="space-y-2" onclick="event.stopPropagation()">
                            {{-- Agregar al carrito --}}
                            <button onclick="agregarAlCarrito(this.closest('.product-card'))"
                                    class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-xs font-bold py-2 rounded-xl transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-cart-plus text-sm"></i>
                                Agregar al carrito
                            </button>
                            <a href="https://wa.me/59176216837" target="_blank"
                               class="w-full bg-[#1b803a] hover:bg-green-700 text-white text-center text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                Cotizar por WhatsApp
                            </a>
                        </div>
                    </div>
 
                </div>
 
            @endforeach
 
        @endforelse
 
    </div>
 
</section>
 
{{-- ===================== MODAL DE PRODUCTO ===================== --}}
<div id="productModal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
     onclick="cerrarModal(event)">
 
    <div class="modal-box bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-2xl w-full max-w-md relative">
 
        {{-- Cerrar --}}
        <button onclick="cerrarModal(null)"
                class="absolute top-3 right-3 z-10 bg-black/40 hover:bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
 
        {{-- Imagen --}}
        <div class="w-full h-56 bg-gray-100 dark:bg-gray-900 overflow-hidden">
            <img id="modalImg" src="" alt="" class="w-full h-full object-cover">
        </div>
 
        {{-- Contenido --}}
        <div class="p-5 space-y-3">
            <p id="modalMarca" class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider"></p>
            <h3 id="modalNombre" class="text-xl font-black text-gray-900 dark:text-white"></h3>
            <p id="modalDesc" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed"></p>
            <p id="modalPrecio" class="text-2xl font-black text-gray-900 dark:text-green-400"></p>
 
            <div class="space-y-2 pt-1">
                <button id="modalCartBtn"
                        class="w-full bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-[#bbf7d0] dark:border-green-700 text-[#15803d] dark:text-green-400 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i>
                    Agregar al carrito
                </button>
                <a id="modalWaBtn" href="https://wa.me/59176216837" target="_blank"
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
    // ---------- ESTADO ----------
    let carrito = [];
    let filtroActual = 'default';
 
    // ---------- CARRITO ----------
    function actualizarBadge() {
        document.getElementById('cartBadge').textContent = carrito.length;
    }
 
    function mostrarToast(nombre) {
        document.getElementById('cartToastMsg').textContent = '"' + nombre + '" agregado al carrito';
        const t = document.getElementById('cartToast');
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(() => t.classList.remove('show'), 2400);
    }
 
    function agregarAlCarrito(card) {
        const item = {
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
 
    // Ver carrito
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
            .map(([n, c]) => '• ' + n + (c > 1 ? ' x' + c : ''))
            .join('\n');
        alert('🛒 Carrito (' + carrito.length + ' item' + (carrito.length > 1 ? 's' : '') + '):\n\n' + texto);
    });
 
    // ---------- MODAL ----------
    let productoModal = null;
 
    function abrirModal(card) {
        productoModal = {
            nombre:      card.dataset.nombre,
            marca:       card.dataset.marca,
            precio:      card.dataset.precio,
            descripcion: card.dataset.descripcion,
            img:         card.dataset.img,
        };
        document.getElementById('modalImg').src         = productoModal.img;
        document.getElementById('modalMarca').textContent  = productoModal.marca;
        document.getElementById('modalNombre').textContent = productoModal.nombre;
        document.getElementById('modalDesc').textContent   = productoModal.descripcion;
        document.getElementById('modalPrecio').textContent = productoModal.precio + ' Bs.';
        document.getElementById('productModal').classList.add('open');
    }
 
    function cerrarModal(event) {
        if (event === null || event.target === document.getElementById('productModal')) {
            document.getElementById('productModal').classList.remove('open');
        }
    }
 
    document.getElementById('modalCartBtn').addEventListener('click', () => {
        if (productoModal) {
            // Simula un card temporal para reutilizar la función
            const fakeCard = { dataset: productoModal };
            carrito.push({ ...productoModal });
            actualizarBadge();
            mostrarToast(productoModal.nombre);
            document.getElementById('productModal').classList.remove('open');
        }
    });
 
    // ---------- BUSCADOR ----------
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.product-card').forEach(card => {
            const haystack = [
                card.dataset.nombre,
                card.dataset.marca,
                card.dataset.categoria,
            ].join(' ').toLowerCase();
            card.closest('.product-card').style.display = haystack.includes(q) ? '' : 'none';
        });
    });
 
    // ---------- FILTROS ----------
    document.getElementById('filterBtn').addEventListener('click', (e) => {
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
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.filter-opt').forEach(b => b.classList.remove('active-opt', 'text-[#22C55E]', 'font-extrabold'));
            btn.classList.add('active-opt', 'text-[#22C55E]', 'font-extrabold');
            filtroActual = btn.dataset.filter;
            document.getElementById('filterDropdown').classList.remove('open');
            document.getElementById('filterChevron').style.transform = '';
            ordenarGrid();
        });
    });
 
    function ordenarGrid() {
        const grid = document.getElementById('productGrid');
        const cards = Array.from(grid.querySelectorAll('.product-card'));
 
        cards.sort((a, b) => {
            if (filtroActual === 'marca')       return (a.dataset.marca || '').localeCompare(b.dataset.marca || '');
            if (filtroActual === 'nombre')      return (a.dataset.nombre || '').localeCompare(b.dataset.nombre || '');
            if (filtroActual === 'categoria')   return (a.dataset.categoria || '').localeCompare(b.dataset.categoria || '');
            if (filtroActual === 'precio_asc')  return parseFloat(a.dataset.precio) - parseFloat(b.dataset.precio);
            if (filtroActual === 'precio_desc') return parseFloat(b.dataset.precio) - parseFloat(a.dataset.precio);
            return 0;
        });
 
        cards.forEach(c => grid.appendChild(c));
    }
</script>
@endsection