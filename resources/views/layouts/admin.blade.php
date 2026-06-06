<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Admin - Casatek')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#060d0a] text-white overflow-x-hidden">

    @yield('contenido')

</body>
</html>