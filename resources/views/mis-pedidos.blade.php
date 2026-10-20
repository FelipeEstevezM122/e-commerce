@extends('layouts.app')

@section('titulo', 'Mis Pedidos')

@section('contenido')

<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="mb-8">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-[#22C55E]/10 text-[#15803d] dark:text-[#4ade80] border border-[#22C55E]/20">
            <i class="fa-solid fa-receipt"></i> Historial
        </span>
        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mt-2">Mis pedidos</h1>
    </div>

    <div id="notAuth" class="hidden text-center py-16 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
        <i class="fa-solid fa-lock text-3xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Debes iniciar sesión para ver tus pedidos.</p>
        <a href="{{ route('iniciarsesion') }}" class="inline-block bg-[#22C55E] hover:bg-[#15803d] text-white px-5 py-2.5 rounded-xl text-sm font-bold">Iniciar sesión</a>
    </div>

    <div id="emptyState" class="hidden text-center py-16 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
        <i class="fa-solid fa-receipt text-3xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Todavía no has hecho ningún pedido.</p>
        <a href="{{ route('productos') }}" class="inline-block bg-[#22C55E] hover:bg-[#15803d] text-white px-5 py-2.5 rounded-xl text-sm font-bold">Ver productos</a>
    </div>

    <div id="ordersList" class="space-y-4"></div>
</div>

<script>
const STATUS_STYLES = {
    pending:   'bg-yellow-50 text-yellow-700 border-yellow-200',
    paid:      'bg-blue-50 text-blue-700 border-blue-200',
    shipped:   'bg-purple-50 text-purple-700 border-purple-200',
    delivered: 'bg-green-50 text-green-700 border-green-200',
    cancelled: 'bg-red-50 text-red-700 border-red-200',
};
const STATUS_LABELS = {
    pending: 'Pendiente', paid: 'Pagado', shipped: 'Enviado', delivered: 'Entregado', cancelled: 'Cancelado'
};

async function cargarPedidos() {
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('notAuth').classList.remove('hidden');
        return;
    }

    try {
        const res  = await fetch('/api/orders', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
        const data = await res.json();
        if (!res.ok) throw new Error();

        const orders = data.datos.data ?? data.datos;

        if (!orders.length) {
            document.getElementById('emptyState').classList.remove('hidden');
            return;
        }

        const list = document.getElementById('ordersList');
        orders.forEach(order => {
            const style = STATUS_STYLES[order.status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
            const label = STATUS_LABELS[order.status] ?? order.status;
            const items = order.items.map(i => `${i.quantity}× ${i.product?.name ?? 'Producto'}`).join(', ');
            const fecha = new Date(order.created_at).toLocaleDateString('es-BO', { day: '2-digit', month: 'short', year: 'numeric' });

            const div = document.createElement('div');
            div.className = 'bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5';
            div.innerHTML = `
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <div>
                        <p class="font-black text-gray-900 dark:text-white">${order.order_number}</p>
                        <p class="text-xs text-gray-400">${fecha}</p>
                    </div>
                    <span class="text-xs font-bold uppercase px-3 py-1 rounded-full border ${style}">${label}</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">${items}</p>
                <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-3">
                    <p class="font-black text-lg text-gray-900 dark:text-white">Bs ${Number(order.total).toFixed(2)}</p>
                    ${order.status === 'pending' ? `<button onclick="cancelarPedido(${order.id}, this)" class="text-xs font-bold text-red-500 hover:text-red-700"><i class="fa-solid fa-xmark mr-1"></i>Cancelar pedido</button>` : ''}
                </div>
            `;
            list.appendChild(div);
        });

    } catch (e) {
        document.getElementById('notAuth').classList.remove('hidden');
    }
}

async function cancelarPedido(orderId, btn) {
    if (!confirm('¿Cancelar este pedido?')) return;
    const token = localStorage.getItem('token');
    btn.disabled = true;
    btn.textContent = 'Cancelando...';

    try {
        const res = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'PATCH',
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error();
        location.reload();
    } catch (e) {
        alert('No se pudo cancelar el pedido.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-xmark mr-1"></i>Cancelar pedido';
    }
}

cargarPedidos();
</script>

@endsection
