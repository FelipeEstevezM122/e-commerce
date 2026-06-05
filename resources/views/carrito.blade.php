@extends('layouts.app')
 
@section('titulo', 'Mi Carrito - Casatek')
 
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
    .carrito-page { font-family: 'DM Sans', sans-serif; }
    .carrito-page h1 { font-family: 'DM Serif Display', serif; }
    .total-price  { font-family: 'DM Serif Display', serif; }
 
    /* Animación de entrada por tarjeta */
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .product-row { animation: slideIn .35s ease both; }
    .product-row:nth-child(2) { animation-delay: .07s; }
    .product-row:nth-child(3) { animation-delay: .14s; }
    .product-row:nth-child(4) { animation-delay: .21s; }
 
    /* Eliminar suave */
    .removing {
        opacity: 0 !important;
        transform: translateX(24px) !important;
        transition: opacity .25s ease, transform .25s ease !important;
    }
 
    /* Qty control */
    .qty-wrap { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .qty-wrap button { width: 36px; height: 36px; background: #f9fafb; border: none; font-size: 18px; font-weight: 700; color: #555; cursor: pointer; transition: background .15s, color .15s; display: flex; align-items: center; justify-content: center; }
    .qty-wrap button:hover { background: #f0fdf4; color: #16a34a; }
    .dark .qty-wrap button { background: #374151; color: #d1d5db; }
    .dark .qty-wrap button:hover { background: #166534; color: #bbf7d0; }
    .qty-val { width: 44px; text-align: center; font-size: 15px; font-weight: 700; }
 
    /* Botón checkout */
    .btn-checkout {
        background: #111827;
        color: #fff;
        border-radius: 14px;
        padding: 15px 24px;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
        transition: background .2s, transform .15s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-checkout:hover { background: #1b803a; transform: translateY(-1px); }
    .dark .btn-checkout { background: #1b803a; }
    .dark .btn-checkout:hover { background: #15803d; }
</style>
@endpush
 
@section('contenido')
 
<div class="carrito-page max-w-6xl mx-auto px-4 py-10">
 
    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-[#22C55E] transition-colors">Inicio</a>
        <span>›</span>
        <a href="{{ url('/productos') }}" class="hover:text-[#22C55E] transition-colors">Productos</a>
        <span>›</span>
        <span class="text-[#22C55E] font-semibold">Mi Carrito</span>
    </nav>
 
    {{-- ENCABEZADO --}}
    <div class="mb-10">
        <p class="text-[10px] font-extrabold text-[#22C55E] tracking-[.18em] uppercase mb-1">Tu compra</p>
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white leading-tight">
            Mi <span class="text-[#22C55E]">Carrito</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
            Revisa tus productos antes de finalizar la compra.
        </p>
    </div>
 
    <div class="grid lg:grid-cols-3 gap-8 items-start">
 
        {{-- ======== COLUMNA IZQUIERDA – PRODUCTOS ======== --}}
        <div class="lg:col-span-2">
 
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 mb-4 tracking-wide" id="items-counter">
                {{ count($carrito ?? []) }} productos en tu carrito
            </p>
 
            <div class="space-y-4" id="cart-list">
 
                @forelse($carrito ?? [] as $item)
                <div class="product-row bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 flex flex-col md:flex-row gap-5 transition-all duration-300"
                     data-id="{{ $item['id'] }}"
                     data-unit="{{ $item['precio'] }}">
 
                    {{-- Imagen --}}
                    <div class="w-24 h-24 md:w-28 md:h-28 shrink-0 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 overflow-hidden flex items-center justify-center p-3">
                        @if(!empty($item['img']))
                            <img src="{{ $item['img'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-contain">
                        @else
                            <img src="{{ asset('images/placeholder-product.png') }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-contain opacity-40">
                        @endif
                    </div>
 
                    {{-- Info --}}
                    <div class="flex-1 flex flex-col justify-between">
 
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-widest">{{ $item['marca'] ?? '' }}</span>
                                <h2 class="text-base font-black text-gray-900 dark:text-white mt-0.5 leading-tight">
                                    {{ $item['nombre'] }}
                                </h2>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 leading-relaxed line-clamp-2">
                                    {{ $item['descripcion'] ?? '' }}
                                </p>
                            </div>
                            {{-- Eliminar --}}
                            <button
                                onclick="eliminar('{{ $item['id'] }}')"
                                class="text-gray-300 dark:text-gray-600 hover:text-red-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-xl transition-all shrink-0"
                                title="Eliminar producto">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
 
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mt-4">
 
                            {{-- Cantidad --}}
                            <div class="qty-wrap">
                                <button onclick="cambiarQty('{{ $item['id'] }}', -1)">−</button>
                                <span class="qty-val text-gray-800 dark:text-white" id="qty-{{ $item['id'] }}">{{ $item['cantidad'] ?? 1 }}</span>
                                <button onclick="cambiarQty('{{ $item['id'] }}', 1)">+</button>
                            </div>
 
                            {{-- Precio --}}
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-semibold text-gray-400">Bs.</span>
                                <span class="text-2xl font-black text-gray-900 dark:text-white" id="price-{{ $item['id'] }}">
                                    {{ number_format(($item['precio'] ?? 0) * ($item['cantidad'] ?? 1), 2) }}
                                </span>
                            </div>
 
                        </div>
                    </div>
                </div>
                @empty
 
                {{-- Estado vacío --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="text-5xl mb-4">🛒</div>
                    <h3 class="text-lg font-black text-gray-700 dark:text-white mb-2">Tu carrito está vacío</h3>
                    <p class="text-sm text-gray-400 mb-6">Agrega productos desde el catálogo para comenzar.</p>
                    <a href="{{ url('/productos') }}"
                       class="inline-flex items-center gap-2 bg-[#1b803a] hover:bg-green-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Ver Catálogo
                    </a>
                </div>
 
                @endforelse
 
            </div>
 
            {{-- GUARDADOS --}}
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-sm font-black text-gray-700 dark:text-white mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-bookmark text-[#22C55E] text-sm"></i>
                    Guardados para después
                </h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Aquí aparecerán los productos que guardes para más adelante.
                </p>
            </div>
 
        </div>
 
        {{-- ======== COLUMNA DERECHA – RESUMEN ======== --}}
        <div class="sticky top-8">
 
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xl dark:shadow-gray-900/40 p-7">
 
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[.15em] mb-6">Resumen del pedido</p>
 
                <div class="space-y-4 mb-6">
 
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                        <span class="font-bold text-gray-800 dark:text-white">
                            Bs. <span id="subtotal">{{ number_format(collect($carrito ?? [])->sum(fn($i) => ($i['precio'] ?? 0) * ($i['cantidad'] ?? 1)), 2) }}</span>
                        </span>
                    </div>
 
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Envío</span>
                        <span class="bg-green-50 dark:bg-green-900/30 text-[#16a34a] dark:text-green-400 border border-[#bbf7d0] dark:border-green-700 text-[11px] font-bold px-2.5 py-0.5 rounded-lg">
                            Gratis
                        </span>
                    </div>
 
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Impuestos</span>
                        <span class="font-bold text-gray-800 dark:text-white">Bs. 0.00</span>
                    </div>
 
                </div>
 
                <div class="border-t border-gray-100 dark:border-gray-700 pt-5 mb-6">
                    <div class="flex justify-between items-end">
                        <span class="text-base font-black text-gray-800 dark:text-white">Total</span>
                        <div class="total-price text-right">
                            <span class="text-sm font-semibold text-gray-400 mr-1">Bs.</span>
                            <span class="text-3xl font-black text-[#22C55E]" id="total">
                                {{ number_format(collect($carrito ?? [])->sum(fn($i) => ($i['precio'] ?? 0) * ($i['cantidad'] ?? 1)), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
 
                {{-- CTA Finalizar --}}
                <button class="btn-checkout w-full">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Finalizar Compra
                </button>
 
                {{-- Seguir comprando --}}
                <a href="{{ url('/productos') }}"
                   class="mt-3 block w-full text-center border-2 border-[#1b803a] text-[#1b803a] dark:text-green-400 dark:border-green-600 hover:bg-[#1b803a] dark:hover:bg-green-700 hover:text-white py-3 rounded-2xl text-sm font-bold transition-all duration-200">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i>
                    Seguir Comprando
                </a>
 
                {{-- Trust badges --}}
                <div class="mt-5 flex items-center justify-center gap-2 text-[11px] text-gray-300 dark:text-gray-600">
                    <i class="fa-solid fa-shield-halved text-[#22C55E]"></i>
                    Pago 100% seguro y protegido
                </div>
 
                <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                    <div class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-truck text-[#22C55E] text-base"></i>
                        Envío gratis
                    </div>
                    <div class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-rotate-left text-[#22C55E] text-base"></i>
                        Garantía
                    </div>
                    <div class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-brands fa-whatsapp text-[#22C55E] text-base"></i>
                        Soporte
                    </div>
                </div>
 
            </div>
 
        </div>
 
    </div>
 
</div>
 
{{-- ===================== SCRIPTS ===================== --}}
<script>
    const precios = {};
    const cantidades = {};
 
    // Poblar desde los data attributes
    document.querySelectorAll('.product-row').forEach(row => {
        const id = row.dataset.id;
        const unit = parseFloat(row.dataset.unit) || 0;
        const qtyEl = document.getElementById('qty-' + id);
        const qty = qtyEl ? parseInt(qtyEl.textContent) || 1 : 1;
        precios[id] = unit;
        cantidades[id] = qty;
    });
 
    function recalcular() {
        let subtotal = 0;
        let count = 0;
 
        document.querySelectorAll('.product-row').forEach(row => {
            const id = row.dataset.id;
            const q = cantidades[id] || 1;
            const p = precios[id] || 0;
            subtotal += q * p;
            count += q;
 
            const priceEl = document.getElementById('price-' + id);
            if (priceEl) priceEl.textContent = (q * p).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        });
 
        const fmt = (n) => n.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const subtotalEl = document.getElementById('subtotal');
        const totalEl    = document.getElementById('total');
        const counter    = document.getElementById('items-counter');
 
        if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
        if (totalEl)    totalEl.textContent    = fmt(subtotal);
        if (counter)    counter.textContent    = count + ' producto' + (count !== 1 ? 's' : '') + ' en tu carrito';
    }
 
    function cambiarQty(id, delta) {
        cantidades[id] = Math.max(1, (cantidades[id] || 1) + delta);
        const qtyEl = document.getElementById('qty-' + id);
        if (qtyEl) qtyEl.textContent = cantidades[id];
        recalcular();
 
        // Aquí puedes hacer un fetch a tu ruta AJAX para actualizar en el servidor:
        // fetch('/carrito/actualizar', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify({ id, cantidad: cantidades[id] }) });
    }
 
    function eliminar(id) {
        const row = document.querySelector('.product-row[data-id="' + id + '"]');
        if (!row) return;
        row.classList.add('removing');
        setTimeout(() => {
            row.remove();
            delete cantidades[id];
            delete precios[id];
            recalcular();
 
            // Verificar si quedó vacío
            const remaining = document.querySelectorAll('.product-row');
            if (remaining.length === 0) {
                document.getElementById('cart-list').innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
                        <div class="text-5xl mb-4">🛒</div>
                        <h3 class="text-lg font-black text-gray-700 dark:text-white mb-2">Tu carrito está vacío</h3>
                        <p class="text-sm text-gray-400 mb-6">Agrega productos desde el catálogo.</p>
                        <a href="/productos" class="inline-flex items-center gap-2 bg-[#1b803a] hover:bg-green-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors">
                            <i class="fa-solid fa-arrow-left text-xs"></i> Ver Catálogo
                        </a>
                    </div>`;
            }
        }, 280);
 
        // Aquí puedes eliminar del servidor:
        // fetch('/carrito/eliminar/' + id, { method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    }
</script>
 
@endsection