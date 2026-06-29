<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompraInsumoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| Rutas Web - Sistema Patio del Majau
|--------------------------------------------------------------------------
|
| Sprint 1: se habilitan las rutas HU por HU según prioridad MoSCoW.
| HU-01 ✅ Inicio de sesión
| HU-02 ⏳ Registrar pedidos
| HU-03 ⏳ Registrar ventas
| HU-08 ⏳ Registrar usuarios
| HU-13 ⏳ Ver menú
| HU-14 ⏳ Registrar productos
| HU-17 ⏳ Generar factura
| HU-19 ⏳ Notificar falta de stock
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// HU-01: login y logout (registro público deshabilitado; usuarios vía HU-08)
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/mesero', [DashboardController::class, 'index'])->name('dashboard.mesero');
    Route::get('/dashboard/cajero', [DashboardController::class, 'index'])->name('dashboard.cajero');
    Route::get('/dashboard/cocinero', [DashboardController::class, 'index'])->name('dashboard.cocinero');

    // HU-13: Menú visible para todo usuario autenticado.
    Route::get('/menu', [ProductoController::class, 'menu'])->name('menu.index');

    // HU-08/HU-14/HU-19: Solo administrador.
    Route::middleware('role:Administrador')->group(function () {
        Route::resource('empleados', EmpleadoController::class)->except(['show']);
        Route::resource('productos', ProductoController::class);
        Route::resource('mesas', MesaController::class)->except(['show']);
        Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::put('inventario/{insumo}', [InventarioController::class, 'update'])->name('inventario.update');
        Route::resource('compras-insumos', CompraInsumoController::class)->only(['index', 'create', 'store', 'show']);
    });

    Route::post('mesas/{mesa}/cambiar-estado', [MesaController::class, 'cambiarEstado'])
        ->middleware('role:Administrador,Mesero')
        ->name('mesas.cambiarEstado');

    // HU-02: Registrar pedidos (mesero y administrador).
    Route::middleware('role:Administrador,Mesero')->group(function () {
        Route::resource('pedidos', PedidoController::class)->only(['index', 'create', 'store', 'show']);
    });
    Route::post('pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])
        ->middleware('role:Administrador,Cocinero,Mesero')
        ->name('pedidos.cambiarEstado');

    // HU-03/HU-17: Ventas y facturas (cajero y administrador).
    Route::middleware('role:Administrador,Cajero')->group(function () {
        Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('ventas/{venta}/factura', [VentaController::class, 'generarFactura'])->name('ventas.factura');
    });
});
