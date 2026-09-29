@extends('layouts.app')

@section('titulo', 'Iniciar Sesión')

@section('contenido')

<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="w-full max-w-sm space-y-6">

        <div class="text-center space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Iniciar sesión</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Accede a tu cuenta de Casatek</p>
        </div>

        <div id="errorDiv" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2 rounded-md"></div>

        <form id="sessionForm" method="POST" action="{{ route('login.admin') }}" class="hidden">
            @csrf
            <input type="hidden" name="email" id="sessionEmail">
            <input type="hidden" name="password" id="sessionPassword">
        </form>

        <form id="loginForm" class="space-y-4">

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="********"
                        class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
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
                <a href="{{ route('recuperar_contrasena') }}" class="text-[#1b803a] hover:underline">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-[#1b803a] hover:bg-[#166a30] text-white py-2 rounded-md text-sm font-medium">
                Iniciar sesión
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                ¿No tienes cuenta?
                <a href="{{ route('registro') }}" class="text-[#1b803a] font-medium hover:underline">Regístrate</a>
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

@endsection
