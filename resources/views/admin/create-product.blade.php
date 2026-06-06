@extends('layouts.app')

@section('titulo', 'Registrar Producto - Casatek')

@section('contenido')

<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-100 my-6">

    <!-- ENCABEZADO -->
    <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-100">
        <span class="w-10 h-10 bg-[#22C55E] rounded-lg flex items-center justify-center text-white text-xl">
            <i class="fa-solid fa-box-open"></i>
        </span>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Añadir Nuevo Producto</h2>
            <p class="text-gray-500 text-xs">Las imágenes se suben a Cloudinary automáticamente</p>
        </div>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- FORMULARIO -->
    {{-- FIX: action apunta a la ruta correcta, method POST, enctype para imágenes --}}
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Nombre -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del Producto *</label>
            {{-- FIX: name="name" (no "nombre") --}}
            <input type="text" name="name" value="{{ old('name') }}"
                placeholder="Ej. Cámara Robótica Exterior"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
        </div>

        <!-- Descripción -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
            <textarea name="description" rows="3"
                placeholder="Descripción del producto..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">{{ old('description') }}</textarea>
        </div>

        <!-- SKU y Precio -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">SKU *</label>
                {{-- FIX: name="sku" --}}
                <input type="text" name="sku" value="{{ old('sku') }}"
                    placeholder="Ej. CAM-001"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Precio base (Bs.) *</label>
                {{-- FIX: name="base_price" (no "precio") --}}
                <input type="number" name="base_price" value="{{ old('base_price') }}"
                    placeholder="0.00" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
            </div>
        </div>

        <!-- Stock y Garantía -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Stock *</label>
                {{-- FIX: name="stock" --}}
                <input type="number" name="stock" value="{{ old('stock', 0) }}"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Días de garantía</label>
                <input type="number" name="warranty_days" value="{{ old('warranty_days', 0) }}"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
            </div>
        </div>

        <!-- Marca y Categoría -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Marca</label>
                <select name="brand_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
                    <option value="">Sin marca</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Categoría</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
                    <option value="">Sin categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Imágenes: hasta 4 -->
        {{-- FIX: name="image1", "image2", "image3", "image4" (no "image") --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Imágenes (máx. 4)</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach([1,2,3,4] as $i)
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Imagen {{ $i }}</label>
                    <input type="file" name="image{{ $i }}" accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#1b803a] hover:file:bg-green-100 cursor-pointer">
                </div>
                @endforeach
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="flex-1 bg-[#22C55E] hover:bg-green-600 text-white font-bold py-3 rounded-lg transition-colors shadow-sm text-sm">
                <i class="fa-solid fa-save mr-2"></i> Guardar Producto
            </button>
            <a href="{{ route('admin.products.index') }}"
                class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg transition-colors text-sm">
                Cancelar
            </a>
        </div>

    </form>

</div>

@endsection
