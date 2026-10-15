<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

//1. LOGIN / LOGOUT DE SESIÓN (para el panel admin blade)
Route::post('/login-admin', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
})->name('login.admin');

Route::post('/logout-admin', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    return redirect('/iniciarsesion');
})->name('logout.admin');

//2. PÁGINAS PÚBLICAS (HTML estáticos de public/Vistas)
// Se mantienen los mismos nombres de ruta para no romper los enlaces de Blade
$pagina = fn (string $archivo) => fn () => response()->file(public_path('Vistas/' . $archivo));

Route::get('/',                     $pagina('index.html'));
Route::get('/nosotros',             $pagina('nosotros.html'))->name('nosotros');
Route::get('/contactanos',          $pagina('contactanos.html'))->name('contactanos');
Route::get('/iniciarsesion',        $pagina('iniciarsesion.html'))->name('iniciarsesion');
Route::get('/carrito',              $pagina('carrito.html'))->name('carrito');
Route::get('/registro',             $pagina('registro.html'))->name('registro');
Route::get('/recuperar_contrasena', $pagina('recuperar_contrasena.html'))->name('recuperar_contrasena');
Route::get('/productos',            $pagina('productos.html'))->name('productos');
Route::get('/mis-tickets',          $pagina('MisTickets.html'))->name('mis-tickets');
// Compatibilidad: enlaces antiguos tipo "productos.html" siguen funcionando
Route::get('/{archivo}.html', function (string $archivo) {
    $ruta = public_path('Vistas/' . $archivo . '.html');
    abort_unless(is_file($ruta), 404);
    return response()->file($ruta);
})->where('archivo', '[A-Za-z0-9_\-]+');

// Rangos públicos
Route::get('ranks',        [RankController::class, 'index']);
Route::get('ranks/{rank}', [RankController::class, 'show']);

//3. PANEL ADMIN (vistas blade, sesión Laravel)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Productos CRUD (index usa SP sp_admin_filtrar_productos)
    Route::get('/products',                [ProductController::class, 'index'])  ->name('products.index');
    Route::get('/products/create',         [ProductController::class, 'create']) ->name('products.create');
    Route::post('/products',               [ProductController::class, 'store'])  ->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])   ->name('products.edit');
    Route::put('/products/{product}',      [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{product}',   [ProductController::class, 'destroy'])->name('products.destroy');

    // Usuarios (index usa SP sp_admin_filtrar_usuarios)
    Route::get('/users',              [AdminController::class, 'users'])      ->name('users.index');
    Route::get('/users/{user}',       [AdminController::class, 'showUser'])   ->name('users.show');
    Route::delete('/users/{user}',    [AdminController::class, 'deleteUser']) ->name('users.destroy');
    Route::post('/users/store-admin', [AdminController::class, 'storeAdmin']) ->name('users.store-admin');

    // Pedidos (index usa SP sp_admin_filtrar_pedidos)
    Route::get('/orders',                  [AdminController::class, 'orders'])            ->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus']) ->name('orders.status');

    // Tickets (index usa SP sp_admin_filtrar_tickets)
    Route::get('/tickets',               [AdminController::class, 'tickets'])            ->name('tickets.index');
    Route::post('/orders/{order}/ticket',[TicketController::class, 'generate'])          ->name('tickets.generate');
});