@extends('layouts.app')

@section('titulo', 'Administración de Productos')

@section('contenido')

<div class="p-6">

    <!-- ENCABEZADO -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Panel de Productos
            </h1>
            <p class="text-gray-500">
                Administración general del catálogo Casatek
            </p>
        </div>

        <button
            class="bg-[#22C55E] hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">
            <i class="fa-solid fa-plus mr-2"></i>
            Agregar Producto
        </button>

    </div>

    <!-- TARJETAS RESUMEN -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm">Productos</h3>
            <p class="text-3xl font-bold text-gray-800">248</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm">Marcas</h3>
            <p class="text-3xl font-bold text-gray-800">18</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm">Stock Bajo</h3>
            <p class="text-3xl font-bold text-gray-800">12</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm">Sin Stock</h3>
            <p class="text-3xl font-bold text-gray-800">5</p>
        </div>

    </div>

    <!-- BUSCADOR -->
    <div class="bg-white rounded-2xl shadow-md p-5 mb-6">

        <div class="flex flex-col md:flex-row gap-4">

            <input
                type="text"
                placeholder="Buscar producto..."
                class="flex-1 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">

            <select class="border rounded-xl px-4 py-3">
                <option>Todas las categorías</option>
                <option>Cámaras</option>
                <option>Alarmas</option>
                <option>Redes</option>
            </select>

            <button
                class="bg-gray-800 hover:bg-black text-white px-6 rounded-xl">
                Buscar
            </button>

        </div>

    </div>

    <!-- TABLA -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-[#111827] text-white px-6 py-4">
            <h2 class="font-bold text-lg">
                Gestión de Productos
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left">Imagen</th>
                        <th class="p-4 text-left">Nombre</th>
                        <th class="p-4 text-left">Marca</th>
                        <th class="p-4 text-left">Categoría</th>
                        <th class="p-4 text-left">Costo</th>
                        <th class="p-4 text-left">Venta</th>
                        <th class="p-4 text-left">Stock</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4">
                            <img
                                src="https://via.placeholder.com/70"
                                class="w-16 h-16 rounded-lg object-cover">
                        </td>

                        <td class="p-4 font-semibold">
                            Cámara IP Full HD
                        </td>

                        <td class="p-4">
                            Hikvision
                        </td>

                        <td class="p-4">
                            Cámaras
                        </td>

                        <td class="p-4 text-red-600 font-bold">
                            Bs. 320
                        </td>

                        <td class="p-4 text-green-600 font-bold">
                            Bs. 450
                        </td>

                        <td class="p-4">
                            35
                        </td>

                        <td class="p-4">
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                Activo
                            </span>
                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-2">

                                <button
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection