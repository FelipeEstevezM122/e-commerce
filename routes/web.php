<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BillingInfoController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatalogoController;

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

//2. RUTAS PÚBLICAS (vistas blade, sin autenticación)
Route::get('/',                     fn() => view('index'));
Route::get('/nosotros',             fn() => view('nosotros'))->name('nosotros');
Route::get('/contactanos',          fn() => view('contactanos'))->name('contactanos');
Route::get('/iniciarsesion',        fn() => view('iniciarsesion'))->name('iniciarsesion');
Route::get('/carrito',              fn() => view('carrito'))->name('carrito');
Route::get('/registro',             fn() => view('registro'))->name('registro');
Route::get('/recuperar_contrasena', fn() => view('recuperar_contrasena'))->name('recuperar_contrasena');

// Catálogo con datos reales de la BD
Route::get('/productos', [CatalogoController::class, 'index'])->name('productos');

// Rangos públicos
Route::get('ranks',        [RankController::class, 'index']);
Route::get('ranks/{rank}', [RankController::class, 'show']);

//3. PANEL ADMIN (vistas blade, sesión Laravel)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Productos CRUD
    Route::get('/products',                [ProductController::class, 'index'])  ->name('products.index');
    Route::get('/products/create',         [ProductController::class, 'create']) ->name('products.create');
    Route::post('/products',               [ProductController::class, 'store'])  ->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])   ->name('products.edit');
    Route::put('/products/{product}',      [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{product}',   [ProductController::class, 'destroy'])->name('products.destroy');

    // Usuarios
    Route::get('/users',           [AdminController::class, 'users'])      ->name('users.index');
    Route::get('/users/{user}',    [AdminController::class, 'showUser'])   ->name('users.show');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser']) ->name('users.destroy');

    // Pedidos
    Route::get('/orders',                  [AdminController::class, 'orders'])            ->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus']) ->name('orders.status');

    // Tickets
    Route::get('/tickets',               [TicketController::class, 'adminIndex'])->name('tickets.index');
    Route::post('/orders/{order}/ticket',[TicketController::class, 'generate'])  ->name('tickets.generate');
});