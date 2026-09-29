@extends('layouts.app')

@section('titulo', 'Registro')

@section('contenido')

<div class="relative min-h-[80vh] flex items-center justify-center p-6 overflow-hidden">
    <div class="absolute -top-24 -right-24 w-72 h-72 bg-[#22C55E]/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#1b803a]/10 rounded-full blur-3xl -z-10"></div>

    <div class="w-full max-w-lg bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-2xl rounded-3xl overflow-hidden">

        {{-- Encabezado con degradado --}}
        <div class="bg-gradient-to-br from-[#111111] via-[#1b803a] to-[#22C55E] px-8 py-8 text-white text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 space-y-2">
                <div class="mx-auto w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-2xl"></i>
                </div>
                <h1 class="text-2xl font-black tracking-tight">Crear Cuenta</h1>
                <p class="text-sm text-white/80">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('iniciarsesion') }}" class="text-white font-bold underline">Inicia sesión</a>
                </p>
            </div>
        </div>

        <div class="p-8 space-y-6">

            <div id="errorDiv" class="hidden bg-red-50 border border-red-200 rounded-xl p-3 text-red-700 text-sm"></div>
            <div id="successDiv" class="hidden bg-green-50 border border-green-200 rounded-xl p-3 text-green-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Cuenta creada. Redirigiendo...
            </div>

            <form id="registerForm" class="space-y-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Nombre completo</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="text" name="name" id="name" placeholder="Ej. Juan Pérez"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    </div>
                    <p id="err_name" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Correo electrónico</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="email" name="email" id="email" placeholder="correo@ejemplo.com"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    </div>
                    <p id="err_email" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Teléfono (opcional)</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="tel" name="phone" id="phone" placeholder="591XXXXXXXX"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">WhatsApp (opcional)</label>
                    <div class="relative">
                        <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="tel" name="whatsapp" id="whatsapp" placeholder="591XXXXXXXX"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                    </div>
                </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Contraseña</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres"
                            class="w-full pl-12 pr-12 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                        <button type="button" onclick="togglePassword('password', 'eye_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#22C55E]">
                            <i class="fa-solid fa-eye" id="eye_password"></i>
                        </button>
                    </div>
                    <p id="err_password" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Confirmar contraseña</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite tu contraseña"
                            class="w-full pl-12 pr-12 py-3 border border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye_password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#22C55E]">
                            <i class="fa-solid fa-eye" id="eye_password_confirmation"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-[#1b803a] hover:bg-[#22C55E] text-white py-3 rounded-xl font-bold tracking-wide shadow-md transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> CREAR CUENTA
                </button>

            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

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

    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Registrando...';
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
        btn.innerHTML = '<i class="fa-solid fa-user-plus"></i> CREAR CUENTA';
        btn.disabled  = false;
    }
});
</script>

@endsection
