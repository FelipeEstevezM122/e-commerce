@extends('layouts.app')

@section('titulo', 'Editar Producto - Casatek')

@section('contenido')

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-100 my-6">

        <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-100">
            <span class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xl">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Editar Producto</h2>
                <p class="text-gray-500 text-xs">SKU: {{ $product->sku }}</p>
            </div>
        </div>

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


        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del Producto *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                    placeholder="Ej. Cámara Robótica Exterior"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
                <textarea name="description" rows="3" placeholder="Descripción del producto..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- SKU y Precio -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Ej. CAM-001"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Precio base (Bs.) *</label>
                    <input type="number" name="base_price" value="{{ old('base_price', $product->base_price) }}"
                        placeholder="0.00" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>
            </div>

            <!-- Stock y Garantía -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Días de garantía</label>
                    <input type="number" name="warranty_days" value="{{ old('warranty_days', $product->warranty_days) }}"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>
            </div>

            <!-- Marca y Categoría -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Marca</label>
                    <select name="brand_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        <option value="">Sin marca</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Categoría</label>
                    <select name="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Imágenes actuales -->
            @php
                $images = array_filter([
                    'image1' => $product->image1,
                    'image2' => $product->image2,
                    'image3' => $product->image3,
                    'image4' => $product->image4,
                ]);
            @endphp

            @if(count($images) > 0)
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Imágenes actuales</label>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($images as $field => $url)
                            <div class="relative group">
                                <img src="{{ $url }}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                                <span class="absolute bottom-1 right-1 bg-black/50 text-white text-xs px-1 rounded">
                                    {{ $field }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Subí una imagen nueva en el campo correspondiente para reemplazarla. Los campos vacíos conservan la
                        imagen actual.
                    </p>
                </div>
            @endif

            <!-- Nuevas imágenes -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Reemplazar imágenes</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([1, 2, 3, 4] as $i)
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">
                                Imagen {{ $i }}
                                @if($product->{"image$i"})
                                    <span class="text-blue-400">(tiene imagen)</span>
                                @endif
                            </label>
                            <input type="file" name="image{{ $i }}" accept="image/jpeg,image/png,image/webp"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition-colors shadow-sm text-sm">
                    <i class="fa-solid fa-save mr-2"></i> Guardar Cambios
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg transition-colors text-sm">
                    Cancelar
                </a>
            </div>

        </form>

    </div>

@endsection