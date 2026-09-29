@extends('layouts.app')

@section('titulo', 'Registro')

@section('contenido')

<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="w-full max-w-sm space-y-6">

        <div class="text-center space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear cuenta</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                ¿Ya tienes cuenta?
                <a href="{{ route('iniciarsesion') }}" class="text-[#1b803a] hover:underline">Inicia sesión</a>
            </p>
        </div>

        <div id="errorDiv" class="hidden bg-red-50 border border-red-200 rounded-md p-3 text-red-700 text-sm"></div>
        <div id="successDiv" class="hidden bg-green-50 border border-green-200 rounded-md p-3 text-green-700 text-sm">
            Cuenta creada. Redirigiendo...
        </div>

        <form id="registerForm" class="space-y-4">

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nombre completo</label>
                <input type="text" name="name" id="name" placeholder="Ej. Juan Pérez"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
                <p id="err_name" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" placeholder="correo@ejemplo.com"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
                <p id="err_email" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Teléfono (opcional)</label>
                <input type="tel" name="phone" id="phone" placeholder="591XXXXXXXX"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">WhatsApp (opcional)</label>
                <input type="tel" name="whatsapp" id="whatsapp" placeholder="591XXXXXXXX"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
                <p id="err_password" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite tu contraseña"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:border-[#1b803a]">
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-[#1b803a] hover:bg-[#166a30] text-white py-2 rounded-md text-sm font-medium">
                Crear cuenta
            </button>

        </form>
    </div>
</div>

<script>
function mostrarError(campo, mensaje) {
    const el = document.getElementById('err_' + campo);
    if (!el) return;
    el.textContent = mensaje;
    el.classList.remove('hidden');
}

function limpiarErrores() {
    ['name', 'email', 'password'].forEach(c => {
        const el = document.getElementById('err_' + c);
        if (el) { el.textContent = ''; el.classList.add('hidden'); }
    });
}

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn        = document.getElementById('submitBtn');
    const errorDiv   = document.getElementById('errorDiv');
    const successDiv = document.getElementById('successDiv');

    btn.textContent = 'Registrando...';
    btn.disabled  = true;
    errorDiv.classList.add('hidden');
    successDiv.classList.add('hidden');
    limpiarErrores();

    try {
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name:                  document.getElementById('name').value.trim(),
                email:                 document.getElementById('email').value.trim(),
                phone:                 document.getElementById('phone').value.trim() || null,
                whatsapp:              document.getElementById('whatsapp').value.trim() || null,
                password:              document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            })
        });

        const data = await res.json();

        if (!res.ok) {
            if (data.errors) {
                Object.entries(data.errors).forEach(([campo, msgs]) => {
                    mostrarError(campo, msgs[0]);
                });
            } else {
                errorDiv.textContent = data.message || 'Error al registrarse';
                errorDiv.classList.remove('hidden');
            }
            return;
        }

        localStorage.setItem('token', data.access_token);
        localStorage.setItem('user',  JSON.stringify(data.user));
        localStorage.setItem('roles', JSON.stringify(data.user.roles ?? []));
        localStorage.setItem('rank',  JSON.stringify(data.user.rank  ?? null));

        successDiv.classList.remove('hidden');

        setTimeout(() => {
            window.location.href = '/productos';
        }, 1500);

    } catch (err) {
        errorDiv.textContent = 'Error de conexión. Intenta de nuevo.';
        errorDiv.classList.remove('hidden');
    } finally {
        btn.textContent = 'Crear cuenta';
        btn.disabled  = false;
    }
});
</script>

@endsection
