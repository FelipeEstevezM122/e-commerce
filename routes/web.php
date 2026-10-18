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

//3. PANEL ADMIN

Route::get('/admin/dashboard',       $pagina('dashboard_admin.html'))     ->name('admin.dashboard');
Route::get('/admin/products/create', $pagina('create_product_admin.html'))->name('admin.products.create');
Route::get('/admin/users',           $pagina('users_admin.html'))         ->name('admin.users.index');
Route::get('/admin/users/{user}',    $pagina('show_admin.html'))          ->name('admin.users.show');
Route::get('/admin/orders',          $pagina('orders_admin.html'))        ->name('admin.orders.index');
Route::get('/admin/tickets',         $pagina('tickets_admin.html'))       ->name('admin.tickets.index');

// todavia no existe un html en Vistas para listar/editar productos, asi que estas 2 siguen usando el panel blade viejo con sesion mientras tanto (Fronten)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/products',               [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit']) ->name('admin.products.edit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/products',             [ProductController::class, 'store'])  ->name('products.store');
    Route::put('/products/{product}',    [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::delete('/users/{user}',    [AdminController::class, 'deleteUser']) ->name('users.destroy');
    Route::post('/users/store-admin', [AdminController::class, 'storeAdmin']) ->name('users.store-admin');

    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::post('/orders/{order}/ticket', [TicketController::class, 'generate'])->name('tickets.generate');
});