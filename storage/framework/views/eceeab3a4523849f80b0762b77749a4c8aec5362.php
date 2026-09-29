<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Casatek - <?php echo $__env->yieldContent('titulo', 'Tienda'); ?></title>

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

    <!-- Incluimos el header -->
    <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Aquí se insertará el contenido de cada vista -->
  <main class="pt-32 max-w-14xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php echo $__env->yieldContent('contenido'); ?>
</main>

</body>

</html><?php /**PATH E:\Casatek\versiones\CASATEKv3\resources\views/layouts/app.blade.php ENDPATH**/ ?>