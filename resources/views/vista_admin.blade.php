@extends('layouts.app')

@section('titulo', 'Administración de Productos')

@section('contenido')

<div class="p-6">

    <!-- ENCABEZADO -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Panel de Productos</h1>
            <p class="text-gray-500">Administración general del catálogo Casatek</p>
        </div>
        {{-- FIX: botón ahora enlaza a la ruta de crear producto --}}
        <a href="{{ route('admin.products.create') }}"
            class="bg-[#22C55E] hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">
            <i class="fa-solid fa-plus mr-2"></i> Agregar Producto
        </a>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    <!-- TARJETAS RESUMEN -->
    {{-- FIX: variables del controller, no números hardcodeados --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm">Productos</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm">Marcas</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalBrands }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm">Stock Bajo</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $lowStock }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm">Sin Stock</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $noStock }}</p>
        </div>
    </div>

    <!-- BUSCADOR -->
    <div class="bg-white rounded-2xl shadow-md p-5 mb-6">
        {{-- FIX: form con action, method GET y name en inputs --}}
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="flex flex-col md:flex-row gap-4">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar producto..."
                    class="flex-1 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">

                {{-- FIX: categorías desde BD, no hardcodeadas --}}
                <select name="category_id" class="border rounded-xl px-4 py-3">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-gray-800 hover:bg-black text-white px-6 rounded-xl">
                    Buscar
                </button>

                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 rounded-xl flex items-center justify-center">
                        Limpiar
                    </a>
                @endif

            </div>
        </form>
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-[#111827] text-white px-6 py-4">
            <h2 class="font-bold text-lg">Gestión de Productos</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Imagen</th>
                        <th class="p-4 text-left">Nombre</th>
                        <th class="p-4 text-left">Marca</th>
                        <th class="p-4 text-left">Categoría</th>
                        <th class="p-4 text-left">Precio</th>
                        <th class="p-4 text-left">Stock</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- FIX: @forelse en lugar de @foreach, dentro de <tbody> --}}
                    @forelse($products as $product)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4">
                            {{-- FIX: image1 (no image) --}}
                            <img src="{{ $product->image1 ?? 'https://via.placeholder.com/70' }}"
                                class="w-16 h-16 rounded-lg object-cover">
                        </td>

                        <td class="p-4">
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                        </td>

                        <td class="p-4">{{ $product->brand->name ?? '-' }}</td>

                        <td class="p-4">{{ $product->category->name ?? '-' }}</td>

                        <td class="p-4 text-green-600 font-bold">
                            Bs. {{ number_format($product->base_price, 2) }}
                        </td>

                        <td class="p-4">
                            <span class="{{ $product->stock <= 10 ? 'text-red-600' : 'text-gray-800' }} font-semibold">
                                {{ $product->stock }}
                            </span>
                        </td>

                        <td class="p-4">
                            @if($product->stock === 0)
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Sin Stock</span>
                            @elseif($product->stock <= 10)
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Stock Bajo</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Activo</span>
                            @endif
                        </td>

                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                {{-- FIX: form con @csrf y @method DELETE --}}
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-400">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        @if($products->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
