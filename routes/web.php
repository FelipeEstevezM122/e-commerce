<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BillingInfoController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS (vistas blade, sin autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/',                     fn() => view('index'));
Route::get('/productos',            fn() => view('productos'))->name('productos');
Route::get('/nosotros',             fn() => view('nosotros'))->name('nosotros');
Route::get('/contactanos',          fn() => view('contactanos'))->name('contactanos');
Route::get('/iniciarsesion',        fn() => view('iniciarsesion'))->name('iniciarsesion');
Route::get('/carrito',              fn() => view('carrito'))->name('carrito');
Route::get('/registro',             fn() => view('registro'))->name('registro');
Route::get('/recuperar_contrasena', fn() => view('recuperar_contrasena'))->name('recuperar_contrasena');

// Rangos públicos
Route::get('ranks',        [RankController::class, 'index']);
Route::get('ranks/{rank}', [RankController::class, 'show']);

/*
|--------------------------------------------------------------------------
| 2. RUTAS DEL PANEL ADMIN (vistas blade, middleware auth de sesión)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
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
    Route::get('/tickets',              [TicketController::class, 'adminIndex']) ->name('tickets.index');
    Route::post('/orders/{order}/ticket',[TicketController::class, 'generate'])  ->name('tickets.generate');
});
