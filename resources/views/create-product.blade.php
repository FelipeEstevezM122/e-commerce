@extends('layouts.app')

@section('titulo', 'Registrar Producto - Casatek')

@section('contenido')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-100 my-6">
    
    <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-100">
        <span class="w-10 h-10 bg-[#22C55E] rounded-lg flex items-center justify-center text-white text-xl">
            <i class="fa-solid fa-box-open"></i>
        </span>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Añadir Nuevo Producto</h2>
            <p class="text-gray-500 text-xs">Sube las imágenes directamente a Cloudinary</p>
        </div>
    </div>

    <form id="uploadForm" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del Producto</label>
            <input type="text" name="nombre" placeholder="Ej. Cámara Robótica Exterior" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#22C55E] focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Imagen desde Computadora</label>
            <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#1b803a] hover:file:bg-green-100 cursor-pointer">
        </div>

        <button type="submit" class="w-full bg-[#22C55E] hover:bg-green-600 text-white font-bold py-3 rounded-lg transition-colors shadow-sm text-sm">
            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Subir a Cloudinary
        </button>
    </form>

    <div id="result" class="mt-6"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $('#result').html(`
            <div class="flex items-center gap-2 text-blue-600 font-medium text-sm">
                <i class="fa-solid fa-spinner animate-spin"></i> Subiendo archivo...
            </div>
        `);
        
        $.ajax({
            url: "{{ route('image.upload') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if(response.success) {
                    $('#result').html(`
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl space-y-3">
                            <p class="text-green-700 font-bold text-sm flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Subido con éxito
                            </p>
                            <img src="${response.image.cloudinary_url}" class="rounded-lg max-h-48 w-auto object-contain shadow-sm border border-gray-100">
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#result').html(`
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 font-medium text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i> Error al procesar la subida
                    </div>
                `);
            }
        });
    });
</script>
@endsection