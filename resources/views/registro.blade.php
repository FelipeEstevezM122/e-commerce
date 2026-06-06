@extends('layouts.app')

@section('titulo', 'Registro')

@section('contenido')

    <div class="min-h-screen flex items-center justify-center p-6">

        <div
            class="w-full max-w-[95vw] min-h-[90vh] grid md:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700">

            <!-- PANEL IZQUIERDO -->
            <div
                class="hidden md:flex flex-col justify-center bg-gradient-to-br from-[#111111] via-[#1b803a] to-[#22C55E] p-12 text-white relative overflow-hidden">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-black/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-5xl font-black tracking-tight mb-4">
                        CASA<span class="text-white">TEK</span>
                    </h1>

                    <h2 class="text-3xl font-bold mb-6">
                        Crea tu Cuenta
                    </h2>

                    <p class="text-white/90 leading-relaxed mb-8">
                        Únete a nuestra plataforma y accede a los mejores productos de seguridad electrónica,
                        telecomunicaciones y automatización.
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
                            <span class="text-sm">Programa de rangos y beneficios</span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- PANEL DERECHO: FORMULARIO -->
            <div class="flex flex-col justify-center bg-white dark:bg-gray-900 p-8 md:p-12">

                <div class="max-w-md w-full mx-auto space-y-6">

                    <div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white">Crear Cuenta</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('iniciarsesion') }}" class="text-[#1b803a] font-semibold hover:underline">
                                Inicia sesión
                            </a>
                        </p>
                    </div>

                    <!-- Mensajes de error -->
                    <div id="errorMessages" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm"></div>

                    <form id="registerForm" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Nombre completo
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Ej. Juan Pérez"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="correo@ejemplo.com"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Contraseña
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Mínimo 8 caracteres"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Confirmar contraseña
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="Repite tu contraseña"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#22C55E] focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
                        </div>

                        <button
                            type="submit"
                            id="submitBtn"
                            class="w-full bg-[#22C55E] hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors shadow-md text-sm">
                            Crear cuenta
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn       = document.getElementById('submitBtn');
            const errorDiv  = document.getElementById('errorMessages');
            btn.textContent = 'Registrando...';
            btn.disabled    = true;
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        name:                  document.getElementById('name').value,
                        email:                 document.getElementById('email').value,
                        password:              document.getElementById('password').value,
                        password_confirmation: document.getElementById('password_confirmation').value,
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('token', data.access_token);
                    window.location.href = '/';
                } else {
                    const errors = data.errors
                        ? Object.values(data.errors).flat().join('<br>')
                        : data.message || 'Error al registrarse';
                    errorDiv.innerHTML = errors;
                    errorDiv.classList.remove('hidden');
                }
            } catch (err) {
                errorDiv.innerHTML = 'Error de conexión. Intenta de nuevo.';
                errorDiv.classList.remove('hidden');
            } finally {
                btn.textContent = 'Crear cuenta';
                btn.disabled    = false;
            }
        });
    </script>

@endsection
