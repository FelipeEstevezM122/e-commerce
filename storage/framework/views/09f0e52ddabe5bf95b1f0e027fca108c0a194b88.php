<?php $__env->startSection('titulo', 'Iniciar Sesión'); ?>

<?php $__env->startSection('contenido'); ?>

<div class="relative min-h-[80vh] flex items-center justify-center p-6 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-72 h-72 bg-[#22C55E]/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-[#1b803a]/10 rounded-full blur-3xl -z-10"></div>

    <div class="w-full max-w-md bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-2xl rounded-3xl overflow-hidden">

        
        <div class="bg-gradient-to-br from-[#111111] via-[#1b803a] to-[#22C55E] px-8 py-8 text-white text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 space-y-2">
                <div class="mx-auto w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-user-shield text-2xl"></i>
                </div>
                <h1 class="text-2xl font-black tracking-tight">Iniciar Sesión</h1>
                <p class="text-sm text-white/80">Bienvenido nuevamente a CASATEK</p>
            </div>
        </div>

        <div class="p-8 space-y-6">

            <div id="errorDiv" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl"></div>

            <form id="sessionForm" method="POST" action="<?php echo e(route('login.admin')); ?>" class="hidden">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="email" id="sessionEmail">
                <input type="hidden" name="password" id="sessionPassword">
            </form>

            <form id="loginForm" class="space-y-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="email" id="email" required placeholder="correo@ejemplo.com"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Contraseña</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="password" id="password" required placeholder="••••••••"
                            class="w-full pl-12 pr-12 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#22C55E]">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 dark:text-gray-300 cursor-pointer">
                        <input type="checkbox" id="remember" class="accent-[#22C55E]">
                        Recordarme
                    </label>
                    <a href="<?php echo e(route('recuperar_contrasena')); ?>" class="text-[#22C55E] hover:underline font-semibold">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-[#1b803a] hover:bg-[#22C55E] text-white py-3 rounded-xl font-bold tracking-wide shadow-md transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> INICIAR SESIÓN
                </button>

                <p class="text-center text-sm text-gray-600 dark:text-gray-300">
                    ¿No tienes cuenta?
                    <a href="<?php echo e(route('registro')); ?>" class="text-[#22C55E] font-bold hover:underline">Regístrate aquí</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn      = document.getElementById('submitBtn');
    const errorDiv = document.getElementById('errorDiv');
    const email    = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Iniciando...';
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
        btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> INICIAR SESIÓN';
        btn.disabled  = false;
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Casatek\versiones\CASATEKv3\resources\views/iniciarsesion.blade.php ENDPATH**/ ?>