<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    OrderController,
    BillingInfoController,
    RankController,
    TicketController
};

// 1. Rutas Públicas (Cualquiera puede ver rangos)
Route::get('ranks', [RankController::class, 'index']);
Route::get('ranks/{rank}', [RankController::class, 'show']);

// 2. Rutas Protegidas (Requieren autenticación via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Facturación
    Route::apiResource('billing-info', BillingInfoController::class);
    Route::patch('billing-info/{billingInfo}/set-default', [BillingInfoController::class, 'setDefault']);
    
    // Pedidos
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
    
    // Tickets
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('tickets/{ticket}', [TicketController::class, 'show']);
    Route::get('orders/{order}/ticket', [TicketController::class, 'showByOrder']);
    
    // Rutas Administrativas (Asumiendo que tienes un middleware 'admin')
    Route::middleware('admin')->group(function () {
        Route::apiResource('ranks', RankController::class)->except(['index', 'show']);
        Route::get('admin/tickets', [TicketController::class, 'adminIndex']);
        Route::post('orders/{order}/generate-ticket', [TicketController::class, 'generate']);
    });
});
Route::get('/', function () {
    return view('index');
});