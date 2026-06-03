<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BillingInfoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\AdminController;


//1. RUTAS PÚBLICAS (sin autenticación)
// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Productos (solo lectura publica)
Route::get('/products',        [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Rangos (solo lectura publica)
Route::get('/ranks',         [RankController::class, 'index']);
Route::get('/ranks/{rank}',  [RankController::class, 'show']);

//2. RUTAS PROTEGIDAS (requieren auth:sanctum)

Route::middleware('auth:sanctum')->group(function () {

    //Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'user']);

    //Productos (crear, editar, eliminar → solo admin via policy)
    Route::post('/products',              [ProductController::class, 'store']);
    Route::put('/products/{product}',     [ProductController::class, 'update']);
    Route::delete('/products/{product}',  [ProductController::class, 'destroy']);

    //Carrito
    Route::prefix('cart')->group(function () {
        Route::get('/',              [CartController::class, 'index']);
        Route::post('/add',          [CartController::class, 'add']);
        Route::put('/items/{itemId}',[CartController::class, 'update']);
        Route::delete('/items/{itemId}', [CartController::class, 'remove']);
        Route::delete('/clear',      [CartController::class, 'clear']);
    });

    //Pedidos 
    Route::prefix('orders')->group(function () {
        Route::get('/',              [OrderController::class, 'index']);
        Route::post('/',             [OrderController::class, 'store']);
        Route::get('/{order}',       [OrderController::class, 'show']);
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel']);

        //Ticket de un pedido especifico
        Route::get('/{order}/ticket', [TicketController::class, 'showByOrder']);
    });

    //Datos de facturacion
    Route::prefix('billing-info')->group(function () {
        Route::get('/',                      [BillingInfoController::class, 'index']);
        Route::post('/',                     [BillingInfoController::class, 'store']);
        Route::get('/{billingInfo}',         [BillingInfoController::class, 'show']);
        Route::put('/{billingInfo}',         [BillingInfoController::class, 'update']);
        Route::patch('/{billingInfo}/default',[BillingInfoController::class, 'setDefault']);
        Route::delete('/{billingInfo}',      [BillingInfoController::class, 'destroy']);
    });

    //Tickets del usuario 
    Route::prefix('tickets')->group(function () {
        Route::get('/',            [TicketController::class, 'index']);
        Route::get('/{ticket}',    [TicketController::class, 'show']);
    });

    //Rangos (crear, editar, eliminar → solo admin via policy)
    Route::post('/ranks',          [RankController::class, 'store']);
    Route::put('/ranks/{rank}',    [RankController::class, 'update']);
    Route::delete('/ranks/{rank}', [RankController::class, 'destroy']);

    //3. RUTAS DE ADMINISTRADOR (auth:sanctum + middleware admin)
    Route::middleware('admin')->prefix('admin')->group(function () {

        // Dashboard y reportes
        Route::get('/dashboard',          [AdminController::class, 'dashboard']);
        Route::get('/reports/sales',      [AdminController::class, 'salesReport']);

        // Reportes con stored procedures
        Route::get('/reports/sales/sp',      [AdminController::class, 'salesReportProcedure']);
        Route::get('/reports/top-products',  [AdminController::class, 'topProductsProcedure']);
        Route::get('/reports/customers',     [AdminController::class, 'customerStatisticsProcedure']);
        Route::get('/reports/inventory',     [AdminController::class, 'inventoryAlertsProcedure']);
        Route::get('/reports/executive',     [AdminController::class, 'executiveDashboardProcedure']);

        // Gestion de usuarios
        Route::get('/users',              [AdminController::class, 'users']);
        Route::post('/users',             [AdminController::class, 'createUser']);
        Route::get('/users/{user}',       [AdminController::class, 'showUser']);
        Route::put('/users/{user}',       [AdminController::class, 'updateUser']);
        Route::delete('/users/{user}',    [AdminController::class, 'deleteUser']);

        // Gestion de productos (admin view)
        Route::get('/products',           [AdminController::class, 'products']);

        // Gestion de pedidos (admin view)
        Route::get('/orders',                          [AdminController::class, 'orders']);
        Route::patch('/orders/{order}/status',         [AdminController::class, 'updateOrderStatus']);

        // Gestion de tickets
        Route::get('/tickets',                         [TicketController::class, 'adminIndex']);
        Route::post('/orders/{order}/ticket',          [TicketController::class, 'generate']);
    });
});
