@extends('layouts.app')
 
@section('titulo', 'Administración de Productos')
 
@section('contenido')
 
<style>
    /* ── Reset header base del layout ── */
    /* Asegúrate de que tu layouts/app.blade.php no renderice
       el nav público cuando el usuario sea administrador.
       Puedes condicionarlo con: @if(!auth()->user()?->is_admin) ... @endif
       en tu layout. Este archivo solo maneja la vista. */
 
    #adminSidebar a { transition: background .15s, color .15s; }
    #adminSidebar a.active,
    #adminSidebar a:hover {
        background: rgba(34,197,94,.12);
        color: #22C55E;
    }
 
    .product-row { transition: background .15s; }
    .product-row:hover { background: rgba(255,255,255,.03); }
 
    /* Toast */
    #adminToast {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity .2s, transform .2s;
        pointer-events: none;
    }
    #adminToast.show { opacity: 1; transform: translateY(0); }
 
    /* Paginación */
    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid #374151;
        color: #d1d5db;
        background: #1f2937;
        text-decoration: none;
        transition: all .15s;
    }
    .pag-btn:hover { border-color: #22C55E; color: #22C55E; background: rgba(34,197,94,.08); }
    .pag-btn.active { background: #22C55E; color: #fff; border-color: #22C55E; }
    .pag-btn.disabled { opacity: .35; pointer-events: none; }
</style>
 
{{-- ═══════════════════════════════════════════════════
     HEADER EXCLUSIVO DE ADMINISTRADOR
     (reemplaza el nav público del layout)
════════════════════════════════════════════════════ --}}
<header class="bg-[#0f172a] border-b border-gray-800 px-6 py-0 flex items-center justify-between h-16 -mx-6 -mt-6 mb-8 sticky top-0 z-40">
 
    {{-- Logo --}}
    <div class="flex items-center gap-3 shrink-0">
        <span class="w-2.5 h-2.5 bg-[#22C55E] rounded-full"></span>
        <span class="text-xl font-black text-white tracking-tight">Casatek</span>
        <span class="bg-[#22C55E] text-white text-[9px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-widest ml-1">
            Panel Admin
        </span>
    </div>
 
    {{-- Nav admin --}}
    <nav class="flex items-center gap-1">
        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-400 hover:text-[#22C55E]
                  {{ request()->routeIs('admin.products.*') ? 'active bg-[#1f2937] !text-[#22C55E]' : '' }}"
           id="adminSidebar">
            <i class="fa-solid fa-box text-xs"></i> Productos
        </a>
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-400 hover:text-[#22C55E]
                  {{ request()->routeIs('admin.products.create') ? 'active bg-[#1f2937] !text-[#22C55E]' : '' }}">
            <i class="fa-solid fa-plus text-xs"></i> Agregar Producto
        </a>
        {{-- Ajusta la ruta según tu sistema --}}
        @if(Route::has('admin.admins.create'))
        <a href="{{ route('admin.admins.create') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-400 hover:text-[#22C55E]
                  {{ request()->routeIs('admin.admins.*') ? 'active bg-[#1f2937] !text-[#22C55E]' : '' }}">
            <i class="fa-solid fa-user-plus text-xs"></i> Agregar Admin
        </a>
        @endif
        @if(Route::has('admin.reportes'))
        <a href="{{ route('admin.reportes') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-400 hover:text-[#22C55E]">
            <i class="fa-solid fa-chart-bar text-xs"></i> Reportes
        </a>
        @endif
    </nav>
 
    {{-- Usuario / Logout --}}
    <div class="flex items-center gap-3 shrink-0">
        <div class="flex items-center gap-2 bg-[#1f2937] border border-gray-700 rounded-xl px-4 py-2">
            <i class="fa-solid fa-circle-user text-[#22C55E] text-sm"></i>
            <span class="text-sm font-bold text-green-300">{{ auth()->user()->name ?? 'Administrador' }}</span>
        </div>
        <form method="POST" action="{{ route('logout.admin') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-400 bg-[#1f2937] border border-gray-700 hover:border-red-500 hover:text-red-400 transition-all">
                <i class="fa-solid fa-right-from-bracket text-xs"></i> Salir
            </button>
        </form>
    </div>
 
</header>
 
{{-- ═══════════════════════════════════════════════════
     CONTENIDO PRINCIPAL
════════════════════════════════════════════════════ --}}
<div class="space-y-8">
 
    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="w-2 h-7 bg-[#22C55E] rounded-full"></span>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">Panel de Productos</h1>
                <p class="text-gray-400 text-sm mt-0.5">Administración general del catálogo Casatek</p>
            </div>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="flex items-center gap-2 bg-[#22C55E] hover:bg-green-600 text-white font-bold px-6 py-3 rounded-xl shadow transition">
            <i class="fa-solid fa-plus text-sm"></i> Agregar Producto
        </a>
    </div>
 
    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-900/40 border border-green-700 text-green-300 px-5 py-3 rounded-xl text-sm font-semibold">
            <i class="fa-solid fa-circle-check text-[#22C55E]"></i>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-300 px-5 py-3 rounded-xl text-sm">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark text-red-400"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
 
    {{-- TARJETAS RESUMEN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
 
        <div class="bg-[#1f2937] rounded-2xl p-5 border-l-4 border-[#22C55E] shadow">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Productos</p>
            <p class="text-3xl font-black text-white mt-1">{{ $totalProducts }}</p>
        </div>
 
        <div class="bg-[#1f2937] rounded-2xl p-5 border-l-4 border-blue-500 shadow">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Marcas</p>
            <p class="text-3xl font-black text-white mt-1">{{ $totalBrands }}</p>
        </div>
 
        <div class="bg-[#1f2937] rounded-2xl p-5 border-l-4 border-yellow-500 shadow">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Stock Bajo</p>
            <p class="text-3xl font-black text-white mt-1">{{ $lowStock }}</p>
        </div>
 
        <div class="bg-[#1f2937] rounded-2xl p-5 border-l-4 border-red-500 shadow">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Sin Stock</p>
            <p class="text-3xl font-black text-white mt-1">{{ $noStock }}</p>
        </div>
 
    </div>
 
    {{-- BUSCADOR --}}
    <div class="bg-[#1f2937] rounded-2xl shadow p-5 border border-gray-700">
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="flex flex-col md:flex-row gap-3">
 
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar producto..."
                        class="w-full pl-11 pr-4 py-2.5 bg-[#111827] border border-gray-700 rounded-xl text-sm text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-[#22C55E] focus:border-[#22C55E] focus:outline-none transition">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-500 text-sm"></i>
                </div>
 
                <select name="category_id"
                    class="bg-[#111827] border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:ring-2 focus:ring-[#22C55E] focus:outline-none md:w-52">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
 
                <button type="submit"
                    class="bg-[#22C55E] hover:bg-green-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
                </button>
 
                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}"
                       class="bg-[#374151] hover:bg-gray-600 text-gray-300 font-semibold px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                @endif
 
            </div>
        </form>
    </div>
 
    {{-- TABLA --}}
    <div class="bg-[#1f2937] rounded-2xl shadow-lg overflow-hidden border border-gray-700">
 
        <div class="bg-[#0f172a] px-6 py-4 flex items-center justify-between border-b border-gray-800">
            <h2 class="font-bold text-white text-base flex items-center gap-2">
                <i class="fa-solid fa-table-list text-[#22C55E] text-sm"></i>
                Gestión de Productos
            </h2>
            <span class="text-xs text-gray-500 font-semibold">
                {{ $products->total() }} productos en total
            </span>
        </div>
 
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#111827] border-b border-gray-800">
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Imagen</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Nombre</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Marca</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Categoría</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Precio</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Stock</th>
                        <th class="p-4 text-left text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="p-4 text-center text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="product-row border-b border-gray-800">
 
                        <td class="p-4">
                            <img src="{{ $product->image1 ?? 'https://via.placeholder.com/70' }}"
                                class="w-14 h-14 rounded-xl object-cover border border-gray-700"
                                onerror="this.src='https://via.placeholder.com/70'">
                        </td>
 
                        <td class="p-4">
                            <p class="font-bold text-white text-sm">{{ $product->name }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">SKU: {{ $product->sku }}</p>
                        </td>
 
                        <td class="p-4 text-gray-300 text-sm font-medium">{{ $product->brand->name ?? '—' }}</td>
 
                        <td class="p-4">
                            <span class="text-[10px] font-extrabold text-[#22C55E] uppercase tracking-wider">
                                {{ $product->category->name ?? '—' }}
                            </span>
                        </td>
 
                        <td class="p-4">
                            <span class="text-[#22C55E] font-black text-sm">Bs. {{ number_format($product->base_price, 2) }}</span>
                        </td>
 
                        <td class="p-4">
                            <span class="font-black text-sm {{ $product->stock <= 10 ? 'text-red-400' : 'text-white' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
 
                        <td class="p-4">
                            @if($product->stock === 0)
                                <span class="bg-red-900/50 text-red-400 border border-red-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide">
                                    Sin Stock
                                </span>
                            @elseif($product->stock <= 10)
                                <span class="bg-yellow-900/40 text-yellow-400 border border-yellow-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide">
                                    Stock Bajo
                                </span>
                            @else
                                <span class="bg-green-900/40 text-green-400 border border-green-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide">
                                    Activo
                                </span>
                            @endif
                        </td>
 
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   title="Editar"
                                   class="w-9 h-9 flex items-center justify-center bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar {{ addslashes($product->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar"
                                        class="w-9 h-9 flex items-center justify-center bg-red-700 hover:bg-red-600 text-white rounded-xl transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
 
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-20 text-center text-gray-500">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block text-gray-700"></i>
                                <p class="font-semibold text-base">No se encontraron productos</p>
                                <p class="text-sm mt-1">Intenta con otra búsqueda o filtro</p>
                                <a href="{{ route('admin.products.index') }}"
                                   class="mt-4 inline-block text-[#22C55E] hover:underline text-sm font-bold">
                                    Ver todos los productos
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        {{-- PAGINACIÓN --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-800 flex items-center justify-center gap-2 flex-wrap">
 
                @if($products->onFirstPage())
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $products->withQueryString()->previousPageUrl() }}" class="pag-btn">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                @endif
 
                @foreach($products->withQueryString()->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach
 
                @if($products->hasMorePages())
                    <a href="{{ $products->withQueryString()->nextPageUrl() }}" class="pag-btn">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right text-xs"></i></span>
                @endif
 
            </div>
            <p class="text-center text-xs text-gray-600 pb-4">
                Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
            </p>
        @endif
 
    </div>
 
</div>
 
{{-- Toast de confirmación (reutilizable desde este panel) --}}
<div id="adminToast"
     class="fixed bottom-6 right-6 z-[200] bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-xl flex items-center gap-2 border border-gray-700">
    <i class="fa-solid fa-circle-check text-[#22C55E]"></i>
    <span id="adminToastMsg">Acción completada</span>
</div>
 
<script>
// Toast automático si hay mensaje de éxito
@if(session('success'))
    (function(){
        const t = document.getElementById('adminToast');
        document.getElementById('adminToastMsg').textContent = '{{ session('success') }}';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    })();
@endif
</script>
@endsection