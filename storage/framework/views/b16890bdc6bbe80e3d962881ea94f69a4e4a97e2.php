<?php $__env->startSection('titulo', 'Inicio'); ?>

<?php $__env->startSection('contenido'); ?>


<section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#22C55E]/10 via-transparent to-transparent"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#22C55E]/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#22C55E]/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <div class="text-center lg:text-left space-y-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase bg-[#22C55E]/10 text-[#15803d] dark:text-[#4ade80] border border-[#22C55E]/20">
                <i class="fa-solid fa-shield-halved"></i> Seguridad electrónica
            </span>

            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">
                Protege lo que más <span class="text-[#22C55E]">importa</span>
            </h1>

            <p class="text-gray-500 dark:text-gray-300 max-w-md mx-auto lg:mx-0 text-base md:text-lg">
                Cámaras, alarmas y sistemas de seguridad electrónica para tu hogar o empresa, con soporte técnico real.
            </p>

            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                <a href="<?php echo e(route('productos')); ?>"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#22C55E] hover:bg-[#15803d] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#22C55E]/20 transition-colors">
                    <i class="fa-solid fa-shop"></i> Ver productos
                </a>
                <a href="<?php echo e(route('contactanos')); ?>"
                   class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 hover:border-[#22C55E] hover:text-[#22C55E] transition-colors">
                    Contáctanos
                </a>
            </div>

            <div class="flex items-center justify-center lg:justify-start gap-6 pt-4 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-[#22C55E]"></i> Garantía incluida
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-[#22C55E]"></i> Envíos a todo Bolivia
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="absolute inset-0 bg-[#22C55E]/10 rounded-3xl rotate-3 -z-10"></div>
            <img src="<?php echo e(asset('images/imagen1.jpg')); ?>" alt="Casatek"
                 class="w-full rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 object-cover">
        </div>
    </div>
</section>


<section class="max-w-6xl mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center">
            <i class="fa-solid fa-truck-fast text-[#22C55E] text-xl mb-2"></i>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Entrega en Bolivia</p>
        </div>
        <div class="p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center">
            <i class="fa-solid fa-award text-[#22C55E] text-xl mb-2"></i>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Garantía en productos</p>
        </div>
        <div class="p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center">
            <i class="fa-brands fa-whatsapp text-[#22C55E] text-xl mb-2"></i>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Soporte por WhatsApp</p>
        </div>
    </div>
</section>


<section class="max-w-6xl mx-auto px-4 pb-16">
    <div class="text-center mb-8">
        <p class="text-xs font-bold uppercase tracking-widest text-[#22C55E] mb-1">Explora</p>
        <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">¿Qué estás buscando?</h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo e(route('productos')); ?>" class="group p-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center hover:border-[#22C55E] hover:shadow-lg transition-all">
            <i class="fa-solid fa-video text-2xl text-gray-400 group-hover:text-[#22C55E] transition-colors mb-3"></i>
            <p class="text-sm font-bold text-gray-700 dark:text-gray-200">Cámaras</p>
        </a>
        <a href="<?php echo e(route('productos')); ?>" class="group p-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center hover:border-[#22C55E] hover:shadow-lg transition-all">
            <i class="fa-solid fa-bell text-2xl text-gray-400 group-hover:text-[#22C55E] transition-colors mb-3"></i>
            <p class="text-sm font-bold text-gray-700 dark:text-gray-200">Alarmas</p>
        </a>
        <a href="<?php echo e(route('productos')); ?>" class="group p-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center hover:border-[#22C55E] hover:shadow-lg transition-all">
            <i class="fa-solid fa-lock text-2xl text-gray-400 group-hover:text-[#22C55E] transition-colors mb-3"></i>
            <p class="text-sm font-bold text-gray-700 dark:text-gray-200">Control de acceso</p>
        </a>
        <a href="<?php echo e(route('productos')); ?>" class="group p-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-center hover:border-[#22C55E] hover:shadow-lg transition-all">
            <i class="fa-solid fa-network-wired text-2xl text-gray-400 group-hover:text-[#22C55E] transition-colors mb-3"></i>
            <p class="text-sm font-bold text-gray-700 dark:text-gray-200">Redes y cableado</p>
        </a>
    </div>
</section>


<section class="max-w-6xl mx-auto px-4 pb-20">
    <div class="relative overflow-hidden rounded-3xl bg-[#111111] px-8 py-12 text-center">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-[#22C55E]/20 rounded-full blur-3xl"></div>
        <h2 class="text-2xl md:text-3xl font-black text-white mb-3">¿Necesitas asesoría para tu proyecto?</h2>
        <p class="text-gray-400 max-w-lg mx-auto mb-6">Escríbenos y te ayudamos a elegir el sistema de seguridad ideal para tu casa o negocio.</p>
        <a href="<?php echo e(route('contactanos')); ?>"
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#22C55E] hover:bg-[#15803d] text-white rounded-xl text-sm font-bold transition-colors">
            <i class="fa-brands fa-whatsapp"></i> Hablar con un asesor
        </a>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Joker\Downloads\CASATEKv2\CASATEKv2\resources\views/index.blade.php ENDPATH**/ ?>