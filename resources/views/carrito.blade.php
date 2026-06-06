@extends('layouts.app')

@section('titulo', 'Mi Carrito - Casatek')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
    .carrito-page { font-family: 'DM Sans', sans-serif; }
    .carrito-page h1, .total-price { font-family: 'DM Serif Display', serif; }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .product-row { animation: slideIn .35s ease both; }
    .product-row:nth-child(2) { animation-delay: .07s; }
    .product-row:nth-child(3) { animation-delay: .14s; }
    .product-row:nth-child(4) { animation-delay: .21s; }

    .removing {
        opacity: 0 !important;
        transform: translateX(24px) !important;
        transition: opacity .25s ease, transform .25s ease !important;
    }

    .qty-wrap { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .qty-wrap button { width: 36px; height: 36px; background: #f9fafb; border: none; font-size: 18px; font-weight: 700; color: #555; cursor: pointer; transition: background .15s, color .15s; display: flex; align-items: center; justify-content: center; }
    .qty-wrap button:hover { background: #f0fdf4; color: #16a34a; }
    .dark .qty-wrap button { background: #374151; color: #d1d5db; }
    .dark .qty-wrap button:hover { background: #166534; color: #bbf7d0; }
    .qty-val { width: 44px; text-align: center; font-size: 15px; font-weight: 700; }

    .btn-checkout {
        background: #111827; color: #fff; border-radius: 14px;
        padding: 15px 24px; font-size: 14px; font-weight: 800;
        letter-spacing: .05em; text-transform: uppercase;
        transition: background .2s, transform .15s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        border: none; width: 100%; cursor: pointer;
    }
    .btn-checkout:hover { background: #1b803a; transform: translateY(-1px); }
    .dark .btn-checkout { background: #1b803a; }
    .dark .btn-checkout:hover { background: #15803d; }
</style>
@endpush

@section('contenido')

<main class="carrito-page max-w-6xl mx-auto px-4 py-10">

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-[#22C55E] transition-colors">Inicio</a>
        <span aria-hidden="true">›</span>
        <a href="{{ url('/productos') }}" class="hover:text-[#22C55E] transition-colors">Productos</a>
        <span aria-hidden="true">›</span>
        <span class="text-[#22C55E] font-semibold" aria-current="page">Mi Carrito</span>
    </nav>

    {{-- ENCABEZADO --}}
    <header class="mb-10">
        <p class="text-[10px] font-extrabold text-[#22C55E] tracking-[.18em] uppercase mb-1">Tu compra</p>
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white leading-tight">
            Mi <span class="text-[#22C55E]">Carrito</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
            Revisa tus productos antes de finalizar la compra.
        </p>
    </header>

    <div class="grid lg:grid-cols-3 gap-8 items-start">

        {{-- ── COLUMNA IZQUIERDA: LISTA DE PRODUCTOS ── --}}
        <section aria-label="Productos en el carrito" class="lg:col-span-2">

            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 mb-4 tracking-wide" id="items-counter">
                0 productos en tu carrito
            </p>

            {{-- Lista de items --}}
            <ol class="space-y-4 list-none" id="cart-list">
                {{-- Renderizado por JS desde localStorage --}}
            </ol>

            {{-- Estado vacío (oculto por defecto, JS lo muestra si aplica) --}}
            <article id="empty-state" class="hidden bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-12 text-center" aria-label="Carrito vacío">
                <p role="img" aria-label="Carrito" class="text-5xl mb-4">🛒</p>
                <h2 class="text-lg font-black text-gray-700 dark:text-white mb-2">Tu carrito está vacío</h2>
                <p class="text-sm text-gray-400 mb-6">Agrega productos desde el catálogo para comenzar.</p>
                <a href="{{ url('/productos') }}"
                   class="inline-flex items-center gap-2 bg-[#1b803a] hover:bg-green-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                    Ver Catálogo
                </a>
            </article>

            {{-- Guardados --}}
            <aside aria-label="Guardados para después" class="mt-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-sm font-black text-gray-700 dark:text-white mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-bookmark text-[#22C55E] text-sm" aria-hidden="true"></i>
                    Guardados para después
                </h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Aquí aparecerán los productos que guardes para más adelante.
                </p>
            </aside>

        </section>

        {{-- ── COLUMNA DERECHA: RESUMEN ── --}}
        <aside aria-label="Resumen del pedido" class="sticky top-8">

            <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xl dark:shadow-gray-900/40 p-7">

                <h2 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[.15em] mb-6">
                    Resumen del pedido
                </h2>

                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                        <dd class="font-bold text-gray-800 dark:text-white">
                            Bs. <span id="subtotal">0.00</span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500 dark:text-gray-400">Envío</dt>
                        <dd class="bg-green-50 dark:bg-green-900/30 text-[#16a34a] dark:text-green-400 border border-[#bbf7d0] dark:border-green-700 text-[11px] font-bold px-2.5 py-0.5 rounded-lg">
                            Gratis
                        </dd>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500 dark:text-gray-400">Impuestos</dt>
                        <dd class="font-bold text-gray-800 dark:text-white">Bs. 0.00</dd>
                    </div>
                </dl>

                <div class="border-t border-gray-100 dark:border-gray-700 pt-5 mb-6">
                    <div class="flex justify-between items-end">
                        <p class="text-base font-black text-gray-800 dark:text-white">Total</p>
                        <div class="total-price text-right">
                            <span class="text-sm font-semibold text-gray-400 mr-1">Bs.</span>
                            <span class="text-3xl font-black text-[#22C55E]" id="total">0.00</span>
                        </div>
                    </div>
                </div>

                <button class="btn-checkout" aria-label="Finalizar compra">
                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                    Finalizar Compra
                </button>

                <a href="{{ url('/productos') }}"
                   class="mt-3 block w-full text-center border-2 border-[#1b803a] text-[#1b803a] dark:text-green-400 dark:border-green-600 hover:bg-[#1b803a] dark:hover:bg-green-700 hover:text-white py-3 rounded-2xl text-sm font-bold transition-all duration-200">
                    <i class="fa-solid fa-arrow-left text-xs mr-1" aria-hidden="true"></i>
                    Seguir Comprando
                </a>

                <p class="mt-5 flex items-center justify-center gap-2 text-[11px] text-gray-300 dark:text-gray-600">
                    <i class="fa-solid fa-shield-halved text-[#22C55E]" aria-hidden="true"></i>
                    Pago 100% seguro y protegido
                </p>

                <ul class="mt-4 grid grid-cols-3 gap-2 text-center list-none" aria-label="Beneficios">
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-truck text-[#22C55E] text-base" aria-hidden="true"></i>
                        Envío gratis
                    </li>
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-rotate-left text-[#22C55E] text-base" aria-hidden="true"></i>
                        Garantía
                    </li>
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-brands fa-whatsapp text-[#22C55E] text-base" aria-hidden="true"></i>
                        Soporte
                    </li>
                </ul>

            </section>
        </aside>

    </div>

</main>

{{-- ── SCRIPTS ── --}}
<script>
const CART_KEY = 'casatek_carrito';

function getToken()   { return localStorage.getItem('token') || null; }
function getCarrito() { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCarrito(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }

function fmt(n) {
    return parseFloat(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function recalcular() {
    const carrito  = getCarrito();
    const subtotal = carrito.reduce((acc, i) => acc + i.precio * i.cantidad, 0);
    const count    = carrito.reduce((acc, i) => acc + i.cantidad, 0);
    document.getElementById('subtotal').textContent = fmt(subtotal);
    document.getElementById('total').textContent    = fmt(subtotal);
    document.getElementById('items-counter').textContent =
        count + ' producto' + (count !== 1 ? 's' : '') + ' en tu carrito';
}

async function cambiarQty(id, delta) {
    const carrito = getCarrito();
    const item    = carrito.find(i => i.id === id);
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + delta);
    saveCarrito(carrito);

    document.getElementById('qty-'   + id).textContent = item.cantidad;
    document.getElementById('price-' + id).textContent = fmt(item.precio * item.cantidad);
    recalcular();

    // Sincronizar con BD si está logueado
    const token = getToken();
    if (!token) return;
    try {
        // Buscar el cart_item_id desde la BD
        const res  = await fetch('/api/cart', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        const bdItem = data.datos?.items?.find(i => String(i.product_id) === String(id));
        if (bdItem) {
            await fetch('/api/cart/items/' + bdItem.id, {
                method:  'PUT',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'Authorization': 'Bearer ' + token,
                },
                body: JSON.stringify({ quantity: item.cantidad }),
            });
        }
    } catch(e) { console.warn('Sync qty error:', e); }
}

async function eliminar(id) {
    const row = document.querySelector('.product-row[data-id="' + id + '"]');
    if (!row) return;
    row.classList.add('removing');
    setTimeout(async () => {
        row.remove();
        const carrito = getCarrito().filter(i => i.id !== id);
        saveCarrito(carrito);
        recalcular();
        if (carrito.length === 0) mostrarVacio();

        // Sincronizar con BD
        const token = getToken();
        if (!token) return;
        try {
            const res  = await fetch('/api/cart', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const data = await res.json();
            const bdItem = data.datos?.items?.find(i => String(i.product_id) === String(id));
            if (bdItem) {
                await fetch('/api/cart/items/' + bdItem.id, {
                    method:  'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
                });
            }
        } catch(e) { console.warn('Sync delete error:', e); }
    }, 280);
}

function mostrarVacio() {
    document.getElementById('empty-state').classList.remove('hidden');
}

function crearArticulo(item, index) {
    const img = item.img || 'https://via.placeholder.com/400x300?text=Sin+imagen';
    const li  = document.createElement('li');
    li.className = 'product-row bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 flex flex-col md:flex-row gap-5 transition-all duration-300';
    li.style.animationDelay = (index * 0.07) + 's';
    li.dataset.id   = item.id;
    li.dataset.unit = item.precio;
    li.innerHTML = `
        <figure class="w-24 h-24 md:w-28 md:h-28 shrink-0 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 overflow-hidden flex items-center justify-center p-3 m-0">
            <img src="${img}" alt="${item.nombre}" class="w-full h-full object-contain"
                 onerror="this.src='https://via.placeholder.com/400x300?text=Sin+imagen'">
        </figure>
        <article class="flex-1 flex flex-col justify-between">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-widest">${item.marca || ''}</span>
                    <h2 class="text-base font-black text-gray-900 dark:text-white mt-0.5 leading-tight">${item.nombre}</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 leading-relaxed line-clamp-2">${item.descripcion || ''}</p>
                </div>
                <button onclick="eliminar('${item.id}')"
                        class="text-gray-300 dark:text-gray-600 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-xl transition-all shrink-0">
                    <i class="fa-solid fa-trash text-sm"></i>
                </button>
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mt-4">
                <div class="qty-wrap">
                    <button onclick="cambiarQty('${item.id}', -1)">−</button>
                    <span class="qty-val text-gray-800 dark:text-white" id="qty-${item.id}">${item.cantidad}</span>
                    <button onclick="cambiarQty('${item.id}', 1)">+</button>
                </div>
                <p class="flex items-baseline gap-1">
                    <span class="text-sm font-semibold text-gray-400">Bs.</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white" id="price-${item.id}">
                        ${fmt(item.precio * item.cantidad)}
                    </span>
                </p>
            </div>
        </article>
    `;
    return li;
}

// ── Cargar carrito al iniciar ──
async function cargarCarrito() {
    const token = getToken();
    const lista = document.getElementById('cart-list');

    if (token) {
        // Usuario logueado → cargar desde BD y sincronizar localStorage
        try {
            const res  = await fetch('/api/cart', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const data = await res.json();
            const bdItems = data.datos?.items ?? [];

            if (bdItems.length > 0) {
                // Convertir items de BD al formato localStorage
                const carritoLocal = bdItems.map(i => ({
                    id:          String(i.product_id),
                    nombre:      i.product?.name        ?? 'Producto',
                    marca:       i.product?.brand?.name ?? '',
                    precio:      parseFloat(i.price_when_added),
                    descripcion: i.product?.description ?? '',
                    img:         i.product?.image1      ?? '',
                    cantidad:    i.quantity,
                }));
                saveCarrito(carritoLocal);
            }
        } catch(e) {
            console.warn('No se pudo cargar carrito desde BD:', e);
        }
    }

    // Renderizar desde localStorage (siempre)
    const carrito = getCarrito();
    if (carrito.length === 0) {
        mostrarVacio();
    } else {
        carrito.forEach((item, i) => lista.appendChild(crearArticulo(item, i)));
    }
    recalcular();
}

document.addEventListener('DOMContentLoaded', cargarCarrito);
</script>

@endsection