<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Rutas web — SOLO páginas HTML estáticas (public/Vistas), sin vistas Blade
|--------------------------------------------------------------------------
| Cada página se abre con el nombre real de su archivo: /nosotros.html,
| /carrito.html, /productos_lista_admin.html, etc.
*/

// Sirve un archivo de public/Vistas
$pagina = fn (string $archivo) => fn () => response()->file(public_path('Vistas/' . $archivo));

// 1. RAÍZ: al abrir el link del servidor se va directo al inicio de sesión
Route::redirect('/', '/iniciarsesion.html');

// "login" lo necesita el middleware 'auth' de Laravel cuando alguien sin sesión entra a una ruta protegida
Route::get('/iniciarsesion.html', $pagina('iniciarsesion.html'))->name('login');

// 2. LOGIN / LOGOUT DE SESIÓN (opcionales: el panel usa token con /api/login y /api/logout)
Route::post('/login-admin', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard_admin.html');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
})->name('login.admin');

Route::post('/logout-admin', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    return redirect('/iniciarsesion.html');
})->name('logout.admin');

// 3. PÁGINAS CON PARÁMETRO (el ID va en la URL; la página lo lee con JavaScript)
Route::get('/show_admin.html/{user}',                $pagina('show_admin.html'))              ->name('admin.users.show');
Route::get('/productos_editar_admin.html/{product}', $pagina('productos_editar_admin.html'))  ->name('admin.products.edit');

// Nombres de ruta que los controladores usan en sus redirecciones (admin.dashboard / admin.products.index)
Route::get('/dashboard_admin.html',       $pagina('dashboard_admin.html'))       ->name('admin.dashboard');
Route::get('/productos_lista_admin.html', $pagina('productos_lista_admin.html')) ->name('admin.products.index');

// 4. TODAS LAS DEMÁS PÁGINAS: /<nombre>.html  ->  public/Vistas/<nombre>.html
//    (nosotros.html, contactanos.html, registro.html, carrito.html, productos.html, MisTickets.html,
//     MisPedidos.html, recuperar_contrasena.html, dashboard_admin.html, users_admin.html, orders_admin.html,
//     tickets_admin.html, create_product_admin.html, productos_lista_admin.html, ...)
Route::get('/{archivo}.html', function (string $archivo) {
    $ruta = public_path('Vistas/' . $archivo . '.html');
    abort_unless(is_file($ruta), 404);
    return response()->file($ruta);
})->where('archivo', '[A-Za-z0-9_\-]+');

// 5. COMPATIBILIDAD TEMPORAL: enlaces viejos sin ".html" redirigen a la ruta nueva.
//    Bórralo cuando ya no quede ningún enlace viejo dentro de tus HTML.
foreach ([
    'nosotros', 'contactanos', 'iniciarsesion', 'carrito', 'registro',
    'recuperar_contrasena', 'productos', 'MisTickets', 'MisPedidos',
] as $legacy) {
    Route::redirect('/' . $legacy, '/' . $legacy . '.html');
}
Route::redirect('/mis-tickets', '/MisTickets.html');
Route::redirect('/mis-pedidos', '/MisPedidos.html');

// 6. RANGOS PÚBLICOS (JSON)
Route::get('ranks',        [RankController::class, 'index']);
Route::get('ranks/{rank}', [RankController::class, 'show']);

// 7. ACCIONES ADMIN POR SESIÓN (JSON) — se dejan tal cual estaban
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/products',             [ProductController::class, 'store'])  ->name('products.store');
    Route::put('/products/{product}',    [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{product}',    [ProductController::class, 'show'])   ->name('products.show');

    Route::delete('/users/{user}',    [AdminController::class, 'deleteUser']) ->name('users.destroy');
    Route::post('/users/store-admin', [AdminController::class, 'storeAdmin']) ->name('users.store-admin');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/orders/{order}/ticket', [TicketController::class, 'generate'])->name('tickets.generate');
});