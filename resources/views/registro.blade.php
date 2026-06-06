@extends('layouts.app')

@section('titulo', 'Registro')

@section('contenido')

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-[95vw] min-h-[90vh] grid md:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700">

        <!-- PANEL IZQUIERDO -->
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-[#111111] via-[#1b803a] to-[#22C55E] p-12 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-black/20 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h1 class="text-5xl font-black tracking-tight mb-4">CASA<span class="text-white">TEK</span></h1>
                <h2 class="text-3xl font-bold mb-6">Crea tu Cuenta</h2>
                <p class="text-white/90 leading-relaxed mb-8">
                    Únete y accede a los mejores productos de seguridad electrónica, telecomunicaciones y automatización.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="text-sm">Acceso a precios especiales</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <span class="text-sm">Seguimiento de pedidos en tiempo real</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="text-sm">Programa de rangos y beneficios — empiezas en Bronce</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL DERECHO -->
        <div class="flex flex-col justify-center bg-white dark:bg-gray-900 p-8 md:p-12 overflow-y-auto">
            <div class="max-w-md w-full mx-auto space-y-5">

                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white">Crear Cuenta</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('iniciarsesion') }}" class="text-[#1b803a] font-semibold hover:underline">Inicia sesión</a>
                    </p>
                </div>

                <!-- Rango inicial info -->
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fa-solid fa-medal text-[#22C55E] text-xl"></i>
                    <p class="text-sm text-green-700 dark:text-green-400">
                        Comenzarás con rango <strong>Bronce</strong> — escala comprando más cada mes.
                    </p>
                </div>

                <!-- ERROR -->
                <div id="errorDiv" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm"></div>

                <!-- ÉXITO -->
                <div id="successDiv" class="hidden bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>¡Cuenta creada! Redirigiendo...</span>
                </div>

                <form id="registerForm" class="space-y-4">

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="text" name="name" id="name" placeholder="Ej. Juan Pérez"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>
                        <p id="err_name" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="email" name="email" id="email" placeholder="correo@ejemplo.com"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>
                        <p id="err_email" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Teléfono <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="tel" name="phone" id="phone" placeholder="591XXXXXXXX"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            WhatsApp <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="tel" name="whatsapp" id="whatsapp" placeholder="591XXXXXXXX"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                            <button type="button" onclick="togglePass('password','eye1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#22C55E]">
                                <i class="fa-solid fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        <p id="err_password" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Confirmar contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-[#22C55E]"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite tu contraseña"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                            <button type="button" onclick="togglePass('password_confirmation','eye2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#22C55E]">
                                <i class="fa-solid fa-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-[#22C55E] hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors shadow-md text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        Crear cuenta
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, iconId) {
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
            // Errores de validación campo por campo
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

        // ── Guardar token y datos ──
        localStorage.setItem('token', data.access_token);
        localStorage.setItem('user',  JSON.stringify(data.user));
        localStorage.setItem('roles', JSON.stringify(data.user.roles ?? []));
        localStorage.setItem('rank',  JSON.stringify(data.user.rank  ?? null));

        // ── Mostrar éxito y redirigir ──
        successDiv.classList.remove('hidden');

        setTimeout(() => {
            // Nuevo usuario siempre es cliente, va al catálogo
            window.location.href = '/productos';
        }, 1500);

    } catch (err) {
        errorDiv.textContent = 'Error de conexión. Intenta de nuevo.';
        errorDiv.classList.remove('hidden');
    } finally {
        btn.innerHTML = '<i class="fa-solid fa-user-plus"></i> Crear cuenta';
        btn.disabled  = false;
    }
});
</script>

@endsection