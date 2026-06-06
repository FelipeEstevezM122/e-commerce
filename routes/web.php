<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    OrderController,
    BillingInfoController,
    RankController,
    TicketController,
    AdminController,
    CatalogoController
};

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS (vistas blade, sin autenticación)
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (auth:sanctum → tickets, pedidos, facturación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Facturación
    Route::apiResource('billing-info', BillingInfoController::class);
    Route::patch('billing-info/{billingInfo}/set-default', [BillingInfoController::class, 'setDefault']);

    // Pedidos
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);

    // Tickets del usuario
    Route::get('tickets',                   [TicketController::class, 'index']);
    Route::get('tickets/{ticket}',          [TicketController::class, 'show']);
    Route::get('orders/{order}/ticket',     [TicketController::class, 'showByOrder']);

    // Rangos (solo admin)
    Route::middleware('admin')->group(function () {
        Route::apiResource('ranks', RankController::class)->except(['index', 'show']);
    });
});

/*
|--------------------------------------------------------------------------
| 3. RUTAS DEL PANEL ADMIN (vistas blade, auth + admin)
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
    Route::get('/tickets',               [TicketController::class, 'adminIndex'])->name('tickets.index');
    Route::post('/orders/{order}/ticket',[TicketController::class, 'generate'])  ->name('tickets.generate');
});