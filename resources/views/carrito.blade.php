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

    #modalQR {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
    #modalQR.open {
        opacity: 1;
        pointer-events: all;
    }
    #modalQR .qr-box {
        transform: scale(0.93);
        transition: transform 0.2s ease;
    }
    #modalQR.open .qr-box {
        transform: scale(1);
    }
</style>
@endpush

@section('contenido')

<main class="carrito-page max-w-6xl mx-auto px-4 py-10">

    <nav class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-[#22C55E] transition-colors">Inicio</a>
        <span>›</span>
        <a href="{{ url('/productos') }}" class="hover:text-[#22C55E] transition-colors">Productos</a>
        <span>›</span>
        <span class="text-[#22C55E] font-semibold">Mi Carrito</span>
    </nav>

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

        <section class="lg:col-span-2">

            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 mb-4 tracking-wide" id="items-counter">
                0 productos en tu carrito
            </p>

            <ol class="space-y-4 list-none" id="cart-list"></ol>

            <article id="empty-state" class="hidden bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
                <p class="text-5xl mb-4">🛒</p>
                <h2 class="text-lg font-black text-gray-700 dark:text-white mb-2">Tu carrito está vacío</h2>
                <p class="text-sm text-gray-400 mb-6">Agrega productos desde el catálogo para comenzar.</p>
                <a href="{{ url('/productos') }}"
                   class="inline-flex items-center gap-2 bg-[#1b803a] hover:bg-green-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Ver Catálogo
                </a>
            </article>

            <div id="btn-pagar-todo-wrap" class="hidden mt-6">
                <button id="btn-pagar-todo"
                        class="w-full bg-[#003087] hover:bg-blue-900 text-white font-black py-4 rounded-2xl text-sm transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fa-solid fa-qrcode text-lg"></i>
                    <span>Pagar todo el carrito con QR BCP</span>
                    <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-lg ml-1">
                        Bs. <span id="total-todos">0.00</span>
                    </span>
                </button>
            </div>

            <aside class="mt-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-sm font-black text-gray-700 dark:text-white mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-bookmark text-[#22C55E] text-sm"></i>
                    Guardados para después
                </h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Aquí aparecerán los productos que guardes para más adelante.
                </p>
            </aside>

        </section>

        <aside class="sticky top-8">
            <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xl p-7">

                <h2 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[.15em] mb-6">
                    Resumen del pedido
                </h2>

                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                        <dd class="font-bold text-gray-800 dark:text-white">Bs. <span id="subtotal">0.00</span></dd>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500 dark:text-gray-400">Envío</dt>
                        <dd class="bg-green-50 dark:bg-green-900/30 text-[#16a34a] dark:text-green-400 border border-[#bbf7d0] dark:border-green-700 text-[11px] font-bold px-2.5 py-0.5 rounded-lg">Gratis</dd>
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

                <button id="btn-pagar-todo-resumen" class="btn-checkout mb-3">
                    <i class="fa-solid fa-qrcode text-sm"></i>
                    Pagar todo con QR BCP
                </button>

                <a href="{{ url('/productos') }}"
                   class="mt-1 block w-full text-center border-2 border-[#1b803a] text-[#1b803a] dark:text-green-400 dark:border-green-600 hover:bg-[#1b803a] dark:hover:bg-green-700 hover:text-white py-3 rounded-2xl text-sm font-bold transition-all duration-200">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i>
                    Seguir Comprando
                </a>

                <p class="mt-5 flex items-center justify-center gap-2 text-[11px] text-gray-300 dark:text-gray-600">
                    <i class="fa-solid fa-shield-halved text-[#22C55E]"></i>
                    Pago 100% seguro y protegido
                </p>

                <ul class="mt-4 grid grid-cols-3 gap-2 text-center list-none">
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-truck text-[#22C55E] text-base"></i>Envío gratis
                    </li>
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-solid fa-rotate-left text-[#22C55E] text-base"></i>Garantía
                    </li>
                    <li class="flex flex-col items-center gap-1 text-[10px] text-gray-400 dark:text-gray-600">
                        <i class="fa-brands fa-whatsapp text-[#22C55E] text-base"></i>Soporte
                    </li>
                </ul>

            </section>
        </aside>
    </div>
</main>

{{-- MODAL QR --}}
<div id="modalQR"
     class="fixed inset-0 bg-black/60 z-[99999] flex items-center justify-center p-4">
    <div class="qr-box bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">

        <div class="bg-[#003087] px-6 py-4 flex items-center justify-between">
            <span class="text-white font-black text-xl tracking-tight">›BCP›</span>
            <button id="btn-cerrar-qr-header" class="text-white/70 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 flex flex-col items-center gap-4">

            <div class="w-full bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-2 text-center">
                <p class="text-xs text-gray-400 font-semibold" id="qr-label">Producto</p>
                <p class="text-sm font-black text-gray-800 dark:text-white truncate" id="qr-producto-nombre"></p>
            </div>

            <div class="bg-white p-3 rounded-2xl shadow-inner border border-gray-100" id="qr-container"></div>

            <div class="text-center">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Monto a pagar</p>
                <p class="text-4xl font-black text-[#003087]">
                    Bs. <span id="qr-monto">0.00</span>
                </p>
            </div>

            <div class="w-full border-t border-gray-100 dark:border-gray-700 pt-4 text-center space-y-1">
                <p class="font-black text-gray-800 dark:text-white text-sm">Hugo Camilo Cussi Suxo</p>
                <p class="text-xs text-gray-400">No. Cta: 201-52029768-3-02</p>
                <p class="text-xs text-gray-400">Vencimiento: 06/06/2028</p>
            </div>

            <div class="w-full bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 text-xs text-blue-700 dark:text-blue-300 text-center">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Escanea desde tu app bancaria. El monto ya está incluido.
            </div>

            <button id="btn-cerrar-qr-footer"
                    class="w-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3 rounded-xl text-sm transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
const CART_KEY = 'casatek_carrito';

function getToken()    { return localStorage.getItem('token') || null; }
function getCarrito()  { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCarrito(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }

function fmt(n) {
    return parseFloat(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function recalcular() {
    const carrito  = getCarrito();
    const subtotal = carrito.reduce((acc, i) => acc + i.precio * i.cantidad, 0);
    const count    = carrito.reduce((acc, i) => acc + i.cantidad, 0);
    document.getElementById('subtotal').textContent    = fmt(subtotal);
    document.getElementById('total').textContent       = fmt(subtotal);
    document.getElementById('total-todos').textContent = fmt(subtotal);
    document.getElementById('items-counter').textContent =
        count + ' producto' + (count !== 1 ? 's' : '') + ' en tu carrito';
    const wrap = document.getElementById('btn-pagar-todo-wrap');
    if (count > 0) wrap.classList.remove('hidden');
    else           wrap.classList.add('hidden');
}

// ── CRC16 + generador QR EMVCo ──
function crc16(str) {
    let crc = 0xFFFF;
    for (let i = 0; i < str.length; i++) {
        crc ^= str.charCodeAt(i) << 8;
        for (let j = 0; j < 8; j++) {
            crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : crc << 1;
        }
    }
    return (crc & 0xFFFF).toString(16).toUpperCase().padStart(4, '0');
}

function generarStringQR(monto) {
    const montoStr   = parseFloat(monto).toFixed(2);
    const montoField = '54' + String(montoStr.length).padStart(2, '0') + montoStr;
    const base = '00020101021132580010bo.com.atc01120428511603030206122015202976830252045999' +
                 '5303068' + montoField +
                 '5802BO5923Hugo Camilo Cussi Suxo6006La Paz6226072220280606193000000021303';
    return base + '6304' + crc16(base + '6304');
}

// ── Modal QR ──
function abrirQR(modo) {
    const carrito = getCarrito();
    if (carrito.length === 0) { alert('Tu carrito está vacío.'); return; }

    let monto, label, nombre;
    if (modo === 'todo') {
        monto  = carrito.reduce((acc, i) => acc + i.precio * i.cantidad, 0);
        label  = 'Pago total — ' + carrito.length + ' producto' + (carrito.length !== 1 ? 's' : '');
        nombre = 'Total carrito';
    } else {
        monto  = modo.precio * modo.cantidad;
        label  = 'Producto individual';
        nombre = modo.nombre;
    }

    document.getElementById('qr-monto').textContent           = fmt(monto);
    document.getElementById('qr-label').textContent           = label;
    document.getElementById('qr-producto-nombre').textContent = nombre;

    // Recrear div para evitar bug de qrcodejs
    const container  = document.getElementById('qr-container');
    container.innerHTML = '';
    const qrDiv      = document.createElement('div');
    container.appendChild(qrDiv);

    new QRCode(qrDiv, {
        text:         generarStringQR(monto),
        width:        220,
        height:       220,
        correctLevel: QRCode.CorrectLevel.M,
    });

    document.getElementById('modalQR').classList.add('open');
}

function cerrarQR() {
    document.getElementById('modalQR').classList.remove('open');
}

// ── Carrito ──
async function cambiarQty(id, delta) {
    const carrito = getCarrito();
    const item    = carrito.find(i => i.id === id);
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + delta);
    saveCarrito(carrito);
    document.getElementById('qty-'   + id).textContent = item.cantidad;
    document.getElementById('price-' + id).textContent = fmt(item.precio * item.cantidad);
    recalcular();

    const token = getToken();
    if (!token) return;
    try {
        const res  = await fetch('/api/cart', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
        const data = await res.json();
        const bdItem = data.datos?.items?.find(i => String(i.product_id) === String(id));
        if (bdItem) {
            await fetch('/api/cart/items/' + bdItem.id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
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

        const token = getToken();
        if (!token) return;
        try {
            const res  = await fetch('/api/cart', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
            const data = await res.json();
            const bdItem = data.datos?.items?.find(i => String(i.product_id) === String(id));
            if (bdItem) {
                await fetch('/api/cart/items/' + bdItem.id, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
                });
            }
        } catch(e) { console.warn('Sync delete error:', e); }
    }, 280);
}

function mostrarVacio() {
    document.getElementById('empty-state').classList.remove('hidden');
    document.getElementById('btn-pagar-todo-wrap').classList.add('hidden');
}

function crearArticulo(item, index) {
    const img = item.img || 'https://via.placeholder.com/400x300?text=Sin+imagen';
    const li  = document.createElement('li');
    li.className = 'product-row bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 flex flex-col md:flex-row gap-5 transition-all duration-300';
    li.style.animationDelay = (index * 0.07) + 's';
    li.dataset.id     = item.id;
    li.dataset.unit   = item.precio;
    li.dataset.nombre = item.nombre;
    li.dataset.precio = item.precio;

    li.innerHTML = `
        <figure class="w-24 h-24 md:w-28 md:h-28 shrink-0 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 overflow-hidden flex items-center justify-center p-3 m-0">
            <img src="${img}" alt="" class="w-full h-full object-contain"
                 onerror="this.src='https://via.placeholder.com/400x300?text=Sin+imagen'">
        </figure>
        <article class="flex-1 flex flex-col justify-between">
            <div class="flex justify-between items-start gap-3">
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-widest">${item.marca || ''}</span>
                    <h2 class="text-base font-black text-gray-900 dark:text-white mt-0.5 leading-tight">${item.nombre}</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 leading-relaxed line-clamp-2">${item.descripcion || ''}</p>
                </div>
                <div class="flex flex-col gap-2 shrink-0">
                    <button data-action="pagar-item" data-id="${item.id}"
                            title="Pagar este producto con QR"
                            class="w-9 h-9 bg-[#003087] hover:bg-blue-900 text-white rounded-xl flex items-center justify-center transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <i class="fa-solid fa-qrcode text-sm pointer-events-none"></i>
                    </button>
                    <button data-action="eliminar-item" data-id="${item.id}"
                            title="Eliminar producto"
                            class="w-9 h-9 text-gray-300 dark:text-gray-600 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl flex items-center justify-center transition-all">
                        <i class="fa-solid fa-trash text-sm pointer-events-none"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mt-4">
                <div class="qty-wrap">
                    <button data-action="qty-menos" data-id="${item.id}">−</button>
                    <span class="qty-val text-gray-800 dark:text-white" id="qty-${item.id}">${item.cantidad}</span>
                    <button data-action="qty-mas" data-id="${item.id}">+</button>
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

async function cargarCarrito() {
    const token = getToken();
    const lista = document.getElementById('cart-list');

    if (token) {
        try {
            const res  = await fetch('/api/cart', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
            const data = await res.json();
            const bdItems = data.datos?.items ?? [];
            if (bdItems.length > 0) {
                saveCarrito(bdItems.map(i => ({
                    id:          String(i.product_id),
                    nombre:      i.product?.name        ?? 'Producto',
                    marca:       i.product?.brand?.name ?? '',
                    precio:      parseFloat(i.price_when_added),
                    descripcion: i.product?.description ?? '',
                    img:         i.product?.image1      ?? '',
                    cantidad:    i.quantity,
                })));
            }
        } catch(e) { console.warn('No se pudo cargar carrito desde BD:', e); }
    }

    const carrito = getCarrito();
    if (carrito.length === 0) {
        mostrarVacio();
    } else {
        carrito.forEach((item, i) => lista.appendChild(crearArticulo(item, i)));
    }
    recalcular();
}

document.addEventListener('DOMContentLoaded', function () {

    cargarCarrito();

    // ── Delegación de eventos: botones dentro de cada producto ──
    document.getElementById('cart-list').addEventListener('click', function(e) {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;

        const action = btn.dataset.action;
        const id     = btn.dataset.id;

        if (action === 'qty-menos')     cambiarQty(id, -1);
        if (action === 'qty-mas')       cambiarQty(id,  1);
        if (action === 'eliminar-item') eliminar(id);

        if (action === 'pagar-item') {
            const row    = document.querySelector('.product-row[data-id="' + id + '"]');
            const nombre = row.dataset.nombre;
            const precio = parseFloat(row.dataset.precio);
            const qty    = parseInt(document.getElementById('qty-' + id).textContent);
            abrirQR({ nombre, precio, cantidad: qty });
        }
    });

    // ── Botones pagar TODO ──
    document.getElementById('btn-pagar-todo').addEventListener('click',        () => abrirQR('todo'));
    document.getElementById('btn-pagar-todo-resumen').addEventListener('click', () => abrirQR('todo'));

    // ── Cerrar modal QR ──
    document.getElementById('btn-cerrar-qr-header').addEventListener('click', cerrarQR);
    document.getElementById('btn-cerrar-qr-footer').addEventListener('click', cerrarQR);
    document.getElementById('modalQR').addEventListener('click', function(e) {
        if (e.target === this) cerrarQR();
    });
});
</script>

@endsection