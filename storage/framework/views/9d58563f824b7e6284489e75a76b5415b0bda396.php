

<?php $__env->startSection('titulo', 'Inicio'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="space-y-16 font-['Poppins']">

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold text-gray-400 tracking-widest uppercase flex items-center gap-2">
                        <span class="w-4 h-[1px] bg-gray-400"></span> Conócenos
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Bienvenido a <span class="text-[#1b803a]">Casatek</span>
                    </h2>
                </div>


                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div
                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="p-2 bg-green-50 text-[#1b803a] rounded-lg">
                            ...
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">Garantía Real</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-300">Soporte post-venta</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="p-2 bg-green-50 text-[#1b803a] rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">Alta Tecnología</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-300">Marcas certificadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagen Corporativa -->
            <div class="relative flex justify-center">
                <div class="absolute inset-0 bg-[#1b803a]/5 rounded-3xl transform rotate-3 scale-102 -z-10"></div>
                <img src="<?php echo e(asset('images/imagen1.jpg')); ?>" alt="Soluciones Casatek"
                    class="w-full max-w-md h-[320px] object-cover rounded-3xl shadow-md border border-gray-100">
            </div>
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.slide-item');
            const buttons = document.querySelectorAll('.dynamic-indicator');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                if (index >= slides.length) index = 0;
                if (index < 0) index = slides.length - 1;

                slides.forEach((slide) => {
                    slide.classList.remove('opacity-100', 'scale-100', 'relative', 'flex', 'flex-col', 'md:flex-row', 'items-center', 'justify-between');
                    slide.classList.add('opacity-0', 'scale-95', 'absolute', 'hidden');
                });
                buttons.forEach((btn) => {
                    btn.classList.remove('text-gray-900', 'border-l-2', 'border-gray-900');
                    btn.classList.add('text-gray-400');
                });

                slides[index].classList.remove('opacity-0', 'scale-95', 'absolute', 'hidden');
                slides[index].classList.add('opacity-100', 'scale-100', 'relative', 'flex', 'flex-col', 'md:flex-row', 'items-center', 'justify-between');

                buttons[index].classList.remove('text-gray-400');
                buttons[index].classList.add('text-gray-900', 'dark:text-white', 'border-l-2', 'border-gray-900', 'dark:border-white');

                currentSlide = index;
            }

            function startAutoSlide() {
                clearInterval(slideInterval);
                slideInterval = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 5000);
            }

            buttons.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    showSlide(index);
                    startAutoSlide();
                });
            });

            startAutoSlide();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Joker\Desktop\CASATE_recortado\CASATEK\resources\views/index.blade.php ENDPATH**/ ?>