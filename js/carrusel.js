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
            btn.classList.remove('text-gray-900', 'dark:text-white', 'border-l-2', 'border-gray-900', 'dark:border-white');
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