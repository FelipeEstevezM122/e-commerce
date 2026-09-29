@extends('layouts.app')

@section('titulo', 'Mi Carrito')

@section('contenido')

<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="mb-8">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-[#22C55E]/10 text-[#15803d] dark:text-[#4ade80] border border-[#22C55E]/20">
            <i class="fa-solid fa-cart-shopping"></i> Carrito
        </span>
        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mt-2">Tu carrito de compras</h1>
    </div>

    {{-- Estado: no autenticado --}}
    <div id="notAuth" class="hidden text-center py-16 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
        <i class="fa-solid fa-lock text-3xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Debes iniciar sesión para ver tu carrito.</p>
        <a href="{{ route('iniciarsesion') }}" class="inline-block bg-[#22C55E] hover:bg-[#15803d] text-white px-5 py-2.5 rounded-xl text-sm font-bold">Iniciar sesión</a>
    </div>

    {{-- Estado: carrito vacío --}}
    <div id="emptyState" class="hidden text-center py-16 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
        <i class="fa-solid fa-cart-shopping text-3xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Tu carrito está vacío.</p>
        <a href="{{ route('productos') }}" class="inline-block bg-[#22C55E] hover:bg-[#15803d] text-white px-5 py-2.5 rounded-xl text-sm font-bold">Ver productos</a>
    </div>

    {{-- Contenido del carrito --}}
    <div id="cartContent" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-3" id="itemsList"></div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 h-fit sticky top-28">
            <h2 class="font-black text-lg text-gray-900 dark:text-white mb-4">Resumen</h2>
            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                <span>Productos</span>
                <span id="sumItems">0</span>
            </div>
            <div class="flex justify-between text-lg font-black text-gray-900 dark:text-white border-t border-gray-100 dark:border-gray-700 pt-3 mt-3">
                <span>Total</span>
                <span id="sumTotal">Bs 0.00</span>
            </div>
            <button id="btnCheckout"
                class="w-full mt-5 bg-[#22C55E] hover:bg-[#15803d] text-white py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                <i class="fa-solid fa-credit-card"></i> Proceder al pago
            </button>
            <div id="cartMsg" class="hidden mt-3 text-sm text-red-600"></div>
        </div>
    </div>
</div>

{{-- MODAL CHECKOUT --}}
<div id="checkoutModal" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#111111] to-[#22C55E] p-6 text-white flex items-center justify-between">
            <div>
                <p class="font-black text-lg">Finalizar compra</p>
                <p class="text-white/70 text-xs">Completa los datos de facturación y pago</p>
            </div>
            <button onclick="cerrarCheckout()" class="w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 space-y-4 max-h-[65vh] overflow-y-auto">
            <div id="checkoutError" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl"></div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Método de pago</label>
                <select id="paymentMethod" class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 text-sm dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    <option value="transferencia">Transferencia bancaria</option>
                    <option value="qr">Pago QR</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="deposito">Depósito</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Dirección de entrega</label>
                <input type="text" id="billAddress" placeholder="Calle, zona, referencia"
                    class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 text-sm dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">NIT (opcional)</label>
                    <input type="text" id="billNit"
                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 text-sm dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Razón social (opcional)</label>
                    <input type="text" id="billBusiness"
                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 text-sm dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">Total a pagar</span>
                <span id="checkoutTotal" class="text-xl font-black text-[#22C55E]">Bs 0.00</span>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-3">
            <button onclick="cerrarCheckout()" class="flex-1 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-bold py-2.5 rounded-xl text-sm">Cancelar</button>
            <button id="btnConfirmarPedido" onclick="confirmarPedido()" class="flex-1 bg-[#22C55E] hover:bg-[#15803d] text-white font-bold py-2.5 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i> Confirmar pedido
            </button>
        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
let cartCache = null;

async function cargarCarrito() {
    if (!token) {
        document.getElementById('notAuth').classList.remove('hidden');
        return;
    }

    try {
        const res  = await fetch('/api/cart', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Error al cargar el carrito');

        cartCache = data.datos;

        if (cartCache.is_empty) {
            document.getElementById('emptyState').classList.remove('hidden');
            return;
        }

        renderCarrito();
        document.getElementById('cartContent').classList.remove('hidden');

    } catch (e) {
        document.getElementById('notAuth').classList.remove('hidden');
    }
}

function renderCarrito() {
    const list = document.getElementById('itemsList');
    list.innerHTML = '';

    cartCache.items.forEach(item => {
        const p = item.product;
        const div = document.createElement('div');
        div.className = 'bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 flex items-center gap-4';
        div.innerHTML = `
            <img src="${p.image1 ?? '/images/imagen1.jpg'}" class="w-16 h-16 rounded-xl object-cover border border-gray-100 dark:border-gray-700">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900 dark:text-white truncate">${p.name}</p>
                <p class="text-xs text-gray-400">Bs ${Number(item.price_when_added).toFixed(2)} c/u</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="cambiarCantidad(${item.id}, ${item.quantity - 1})" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 hover:border-[#22C55E] hover:text-[#22C55E]">−</button>
                <span class="w-8 text-center font-bold text-gray-900 dark:text-white">${item.quantity}</span>
                <button onclick="cambiarCantidad(${item.id}, ${item.quantity + 1})" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 hover:border-[#22C55E] hover:text-[#22C55E]">+</button>
            </div>
            <p class="w-24 text-right font-black text-gray-900 dark:text-white">Bs ${(item.price_when_added * item.quantity).toFixed(2)}</p>
            <button onclick="quitarItem(${item.id})" class="w-9 h-9 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center">
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        `;
        list.appendChild(div);
    });

    document.getElementById('sumItems').textContent = cartCache.total_items;
    document.getElementById('sumTotal').textContent = 'Bs ' + Number(cartCache.total_price).toFixed(2);
    document.getElementById('checkoutTotal').textContent = 'Bs ' + Number(cartCache.total_price).toFixed(2);
}

async function cambiarCantidad(itemId, nuevaCantidad) {
    if (nuevaCantidad < 1) return quitarItem(itemId);
    const res = await fetch(`/api/cart/items/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ quantity: nuevaCantidad })
    });
    const data = await res.json();
    if (!res.ok) { mostrarMsg(data.message); return; }
    cargarCarrito();
}

async function quitarItem(itemId) {
    await fetch(`/api/cart/items/${itemId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
    });
    location.reload();
}

function mostrarMsg(msg) {
    const el = document.getElementById('cartMsg');
    el.textContent = msg;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

document.getElementById('btnCheckout').addEventListener('click', () => {
    document.getElementById('checkoutModal').classList.remove('hidden');
    document.getElementById('checkoutModal').classList.add('flex');
});

function cerrarCheckout() {
    document.getElementById('checkoutModal').classList.add('hidden');
    document.getElementById('checkoutModal').classList.remove('flex');
}

async function confirmarPedido() {
    const btn = document.getElementById('btnConfirmarPedido');
    const errorDiv = document.getElementById('checkoutError');
    errorDiv.classList.add('hidden');

    const items = cartCache.items.map(i => ({ product_id: i.product_id, quantity: i.quantity }));

    const body = {
        items,
        payment_method: document.getElementById('paymentMethod').value,
        billing: {
            address: document.getElementById('billAddress').value || null,
            nit: document.getElementById('billNit').value || null,
            business_name: document.getElementById('billBusiness').value || null,
        }
    };

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Procesando...';

    try {
        const res = await fetch('/api/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'No se pudo crear el pedido');

        await fetch('/api/cart/clear', { method: 'DELETE', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token } });

        window.location.href = '{{ route("mis_pedidos") }}';

    } catch (e) {
        errorDiv.textContent = e.message;
        errorDiv.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Confirmar pedido';
    }
}

cargarCarrito();
</script>

@endsection
