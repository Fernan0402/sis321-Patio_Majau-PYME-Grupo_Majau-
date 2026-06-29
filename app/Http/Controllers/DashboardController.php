<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Venta;
use App\Models\Insumo;

/**
 * Panel principal tras el inicio de sesión (HU-01).
 * Las métricas se completarán con HU-02, HU-03 y HU-19.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $totalPedidosHoy = Pedido::whereDate('created_at', today())->count();
        $totalVentasHoy = Venta::whereDate('created_at', today())->sum('monto_total');
        $insumosStockBajo = Insumo::where('activo', true)->whereColumn('stock_actual', '<=', 'stock_minimo')->count();
        $pedidosPendientes = Pedido::where('estado', 'Pendiente')->count();
        $alertasStock = Insumo::where('activo', true)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        return view('dashboard', compact(
            'totalPedidosHoy',
            'totalVentasHoy',
            'insumosStockBajo',
            'pedidosPendientes',
            'alertasStock'
        ));
    }
}
