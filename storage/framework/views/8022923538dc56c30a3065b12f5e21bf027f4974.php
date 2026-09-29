<?php $__env->startSection('titulo', 'Iniciar Sesión'); ?>

<?php $__env->startSection('contenido'); ?>

<div class="relative min-h-[80vh] flex items-center justify-center p-6 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-72 h-72 bg-[#22C55E]/10 rounded-full blur-3xl -z-10"></div>

    <div class="w-full max-w-sm bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl rounded-2xl p-8 space-y-6">

        <div class="text-center space-y-2">
            <div class="mx-auto w-12 h-12 rounded-xl bg-[#22C55E]/10 flex items-center justify-center">
                <i class="fa-solid fa-lock text-[#22C55E]"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Iniciar sesión</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Accede a tu cuenta de Casatek</p>
        </div>

        <div id="errorDiv" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2 rounded-xl"></div>

        <form id="sessionForm" method="POST" action="<?php echo e(route('login.admin')); ?>" class="hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="email" id="sessionEmail">
            <input type="hidden" name="password" id="sessionPassword">
        </form>

        <form id="loginForm" class="space-y-4">

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#22C55E]">
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="********"
                        class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#22C55E]">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                        <span id="eyeIcon">Ver</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <input type="checkbox" id="remember">
                    Recordarme
                </label>
                <a href="<?php echo e(route('recuperar_contrasena')); ?>" class="text-[#22C55E] hover:underline">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-[#22C55E] hover:bg-[#15803d] text-white py-2 rounded-xl text-sm font-medium">
                Iniciar sesión
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                ¿No tienes cuenta?
                <a href="<?php echo e(route('registro')); ?>" class="text-[#22C55E] font-medium hover:underline">Regístrate</a>
            </p>

        </form>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const label = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        label.textContent = 'Ocultar';
    } else {
        input.type = 'password';
        label.textContent = 'Ver';
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn      = document.getElementById('submitBtn');
    const errorDiv = document.getElementById('errorDiv');
    const email    = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    btn.textContent = 'Iniciando...';
    btn.disabled  = true;
    errorDiv.classList.add('hidden');

    try {
        const res  = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || 'Credenciales incorrectas');
        }

        localStorage.setItem('token', data.access_token);
        localStorage.setItem('user',  JSON.stringify(data.user));
        localStorage.setItem('roles', JSON.stringify(data.user.roles ?? []));
        localStorage.setItem('rank',  JSON.stringify(data.user.rank  ?? null));

        const roles   = data.user.roles ?? [];
        const esAdmin = roles.some(r => r.name === 'admin');

        if (esAdmin) {
            document.getElementById('sessionEmail').value    = email;
            document.getElementById('sessionPassword').value = password;
            document.getElementById('sessionForm').submit();
        } else {
            window.location.href = '/';
        }

    } catch (err) {
        errorDiv.textContent = err.message;
        errorDiv.classList.remove('hidden');
        btn.textContent = 'Iniciar sesión';
        btn.disabled  = false;
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Joker\Downloads\CASATEKv2\CASATEKv2\resources\views/iniciarsesion.blade.php ENDPATH**/ ?>