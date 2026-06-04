<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casatek - @yield('titulo', 'Tienda')</title>
    <!-- Enlaces globales del sitio -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">

    <!-- Incluimos el header limpio que optimizamos -->
    @include('partials.header')

    <!-- Aquí se insertará mágicamente el contenido de cada página -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-12">
        @yield('contenido')
    </main>

</body>
</html>