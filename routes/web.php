<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
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
    return redirect()->route('dashboard');
});

// HU-01: login y logout (registro público deshabilitado; usuarios vía HU-08)
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// Se deja sin middleware auth mientras se estabiliza HU-01.
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// HU-08: Registrar usuarios (empleados).
Route::resource('empleados', EmpleadoController::class)->except(['show']);

// HU-14: Registrar productos (CRUD) y HU-13: Ver menú.
Route::resource('productos', ProductoController::class);
Route::get('/menu', [ProductoController::class, 'menu'])->name('menu.index');

// HU-02: Registrar pedidos + cambio de estado.
Route::resource('pedidos', PedidoController::class)->only(['index', 'create', 'store', 'show']);
Route::post('pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');

// HU-03/HU-17: Ventas y facturas.
Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
Route::get('ventas/{venta}/factura', [VentaController::class, 'generarFactura'])->name('ventas.factura');
