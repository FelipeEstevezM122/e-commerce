@extends('layouts.app')

@section('titulo', 'Dashboard Admin - Casatek')

@section('contenido')

<div class="min-h-screen bg-gray-950 text-white p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">
                Panel <span class="text-[#22C55E]">Administrativo</span>
            </h1>
            <p class="text-gray-400 text-sm mt-1">Resumen general de Casatek</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
               class="bg-[#22C55E] hover:bg-green-400 text-black font-bold px-5 py-2 rounded-xl text-sm transition">
                <i class="fa-solid fa-box mr-2"></i>Productos
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="bg-gray-800 hover:bg-gray-700 text-white font-bold px-5 py-2 rounded-xl text-sm transition">
                <i class="fa-solid fa-clipboard-list mr-2"></i>Pedidos
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="bg-gray-800 hover:bg-gray-700 text-white font-bold px-5 py-2 rounded-xl text-sm transition">
                <i class="fa-solid fa-users mr-2"></i>Usuarios
            </a>
        </div>
    </div>

    <!-- TARJETAS PRINCIPALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-[#22C55E] transition">
            <div class="flex justify-between items-start mb-3">
                <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest">Usuarios</span>
                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-blue-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-white mb-1">{{ $totalUsers }}</p>
            <div class="flex gap-3 text-xs text-gray-500 mt-2">
                <span><span class="text-green-400 font-bold">{{ $finalCustomers }}</span> clientes</span>
                <span><span class="text-yellow-400 font-bold">{{ $wholesalers }}</span> mayoristas</span>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-[#22C55E] transition">
            <div class="flex justify-between items-start mb-3">
                <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest">Productos</span>
                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-box text-green-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-white mb-1">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-500">en catálogo</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-[#22C55E] transition">
            <div class="flex justify-between items-start mb-3">
                <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest">Pedidos</span>
                <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list text-purple-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-white mb-1">{{ $totalOrders }}</p>
            <div class="flex gap-3 text-xs text-gray-500 mt-2">
                <span><span class="text-yellow-400 font-bold">{{ $pendingOrders }}</span> pendientes</span>
                <span><span class="text-green-400 font-bold">{{ $completedOrders }}</span> entregados</span>
            </div>
        </div>

        <div class="bg-gray-900 border border-[#22C55E]/40 rounded-2xl p-5 hover:border-[#22C55E] transition">
            <div class="flex justify-between items-start mb-3">
                <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest">Ventas</span>
                <div class="w-10 h-10 bg-[#22C55E]/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-bolivar-sign text-[#22C55E]"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-[#22C55E] mb-1">
                Bs. {{ number_format($totalSales, 0, '.', ',') }}
            </p>
            <p class="text-xs text-gray-500">pedidos entregados</p>
        </div>

    </div>

    <!-- FILA INFERIOR: Top Productos + Ventas por Mes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- TOP PRODUCTOS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="font-bold text-white">
                    <i class="fa-solid fa-trophy text-yellow-400 mr-2"></i>Top Productos Vendidos
                </h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse($topProducts as $i => $product)
                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-800 transition">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black
                        {{ $i === 0 ? 'bg-yellow-400/20 text-yellow-400' : ($i === 1 ? 'bg-gray-400/20 text-gray-400' : ($i === 2 ? 'bg-orange-400/20 text-orange-400' : 'bg-gray-800 text-gray-500')) }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-semibold truncate">{{ $product->name }}</p>
                    </div>
                    <span class="bg-[#22C55E]/20 text-[#22C55E] text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                        {{ $product->total_sold }} vendidos
                    </span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                    <p class="text-sm">Sin ventas registradas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- VENTAS POR MES -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800">
                <h2 class="font-bold text-white">
                    <i class="fa-solid fa-chart-line text-[#22C55E] mr-2"></i>Ventas Últimos 6 Meses
                </h2>
            </div>
            <div class="p-4">
                @php
                    $months = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    $maxSale = $salesByMonth->max('total') ?: 1;
                @endphp
                @forelse($salesByMonth as $sale)
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-gray-400 text-xs w-8 text-right">{{ $months[$sale->month] ?? $sale->month }}</span>
                    <div class="flex-1 bg-gray-800 rounded-full h-6 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#1b803a] to-[#22C55E] rounded-full flex items-center justify-end pr-2 transition-all"
                             style="width: {{ max(5, ($sale->total / $maxSale) * 100) }}%">
                        </div>
                    </div>
                    <span class="text-white text-xs font-bold w-24 text-right">
                        Bs. {{ number_format($sale->total, 0, '.', ',') }}
                    </span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fa-solid fa-chart-line text-3xl mb-2"></i>
                    <p class="text-sm">Sin datos de ventas</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- ACCESOS RÁPIDOS -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.products.create') }}"
           class="bg-gray-900 border border-gray-800 hover:border-[#22C55E] rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-plus text-2xl text-[#22C55E] mb-2 block"></i>
            <p class="text-sm text-gray-300 group-hover:text-white transition">Nuevo Producto</p>
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="bg-gray-900 border border-gray-800 hover:border-yellow-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-clock text-2xl text-yellow-400 mb-2 block"></i>
            <p class="text-sm text-gray-300 group-hover:text-white transition">Pedidos Pendientes</p>
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="bg-gray-900 border border-gray-800 hover:border-blue-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-user-plus text-2xl text-blue-400 mb-2 block"></i>
            <p class="text-sm text-gray-300 group-hover:text-white transition">Gestionar Usuarios</p>
        </a>
        <a href="{{ route('admin.tickets.index') }}"
           class="bg-gray-900 border border-gray-800 hover:border-purple-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-ticket text-2xl text-purple-400 mb-2 block"></i>
            <p class="text-sm text-gray-300 group-hover:text-white transition">Ver Tickets</p>
        </a>
    </div>

    <!-- CERRAR SESIÓN -->
    <div class="mt-6 flex justify-end">
        <form method="POST" action="{{ route('logout.admin') }}">
            @csrf
            <button type="submit"
                class="bg-red-500/20 hover:bg-red-500/40 text-red-400 font-bold px-5 py-2 rounded-xl text-sm transition">
                <i class="fa-solid fa-right-from-bracket mr-2"></i>Cerrar Sesión
            </button>
        </form>
    </div>

</div>

@endsection
