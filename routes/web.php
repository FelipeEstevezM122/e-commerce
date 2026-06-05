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
//Para View
Route::get('/', function () {
    return view('index');
});
// NUEVA: Ruta del Catálogo (Muestra los productos cargados)
Route::get('/productos', function () {
    // Nota: Aquí tus compañeros luego cambiarán esto para pasarle la variable $images desde el controlador
    return view('productos'); 
});
// NUEVA: Ruta para la sección de Nosotros
Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');
//contactanos
Route::get('/contactanos', function () {
    return view('contactanos');
})->name('contactanos');
//iniciarsesion
Route::get('/iniciarsesion', function () {
    return view('iniciarsesion');
})->name('iniciarsesion');
//carrito
Route::get('/carrito', function () {
    return view('carrito');
})->name('carrito');
//registro
Route::get('/registro', function () {
    return view('registro');
})->name('registro');
//olvidarcontraseña
// Mostrar el formulario
Route::get('/recuperar_contraseña', function () {
    return view('recuperar_contraseña');
})->name('password.request');