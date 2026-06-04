<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    OrderController_con_policy,
    BillingInfoController_con_policy,
    RankController_con_policy,
    TicketController_con_policy
};

// 1. Rutas Públicas (Cualquiera puede ver rangos)
Route::get('ranks', [RankController_con_policy::class, 'index']);
Route::get('ranks/{rank}', [RankController_con_policy::class, 'show']);

// 2. Rutas Protegidas (Requieren autenticación via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Facturación
    Route::apiResource('billing-info', BillingInfoController_con_policy::class);
    Route::patch('billing-info/{billingInfo}/set-default', [BillingInfoController_con_policy::class, 'setDefault']);
    
    // Pedidos
    Route::apiResource('orders', OrderController_con_policy::class)->only(['index', 'show', 'store']);
    Route::post('orders/{order}/cancel', [OrderController_con_policy::class, 'cancel']);
    
    // Tickets
    Route::get('tickets', [TicketController_con_policy::class, 'index']);
    Route::get('tickets/{ticket}', [TicketController_con_policy::class, 'show']);
    Route::get('orders/{order}/ticket', [TicketController_con_policy::class, 'showByOrder']);
    
    // Rutas Administrativas (Asumiendo que tienes un middleware 'admin')
    Route::middleware('admin')->group(function () {
        Route::apiResource('ranks', RankController_con_policy::class)->except(['index', 'show']);
        Route::get('admin/tickets', [TicketController_con_policy::class, 'adminIndex']);
        Route::post('orders/{order}/generate-ticket', [TicketController_con_policy::class, 'generate']);
    });
});