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

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-white dark:bg-gray-900 dark:text-white transition-all duration-300 overflow-x-hidden">

    <!-- Incluimos el header limpio que optimizamos -->
    @include('partials.header')

    <!-- Aquí se insertará mágicamente el contenido de cada página -->
  <main class="pt-32 max-w-14xl mx-auto px-4 sm:px-6 lg:px-8">
    @yield('contenido')
</main>

    @include('partials.footer')

</body>

</html>