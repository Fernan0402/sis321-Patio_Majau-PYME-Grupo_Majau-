<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Panel principal tras el inicio de sesión (HU-01).
 * Las métricas se completarán con HU-02, HU-03 y HU-19.
 */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subDays(6)->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $kpis = $this->buildKpis($fechaInicio, $fechaFin);

        $alertasStock = Insumo::where('activo', true)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        return view('dashboard', compact('kpis', 'alertasStock', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Sprint 3 / Actividad 7:
     * Endpoint JSON para KPIs y series del dashboard.
     */
    public function kpis(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subDays(6)->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $kpis = $this->buildKpis($fechaInicio, $fechaFin);
        return response()->json([
            'success' => true,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'data' => $kpis,
        ]);
    }

    private function buildKpis(string $fechaInicio, string $fechaFin): array
    {
        // Consulta única optimizada para KPIs agregados principales.
        $resumen = DB::selectOne(
            "SELECT
                (SELECT COUNT(*) FROM ventas v WHERE DATE(v.created_at) BETWEEN ? AND ?) AS ventas_periodo,
                (SELECT COALESCE(SUM(v.monto_total),0) FROM ventas v WHERE DATE(v.created_at) BETWEEN ? AND ?) AS total_vendido,
                (SELECT COUNT(*) FROM pedidos p WHERE DATE(p.created_at) BETWEEN ? AND ?) AS pedidos_periodo,
                (SELECT COUNT(*) FROM pedidos p WHERE p.estado = 'Pendiente') AS pedidos_pendientes,
                (SELECT COUNT(*) FROM insumos i WHERE i.activo = 1 AND i.stock_actual <= i.stock_minimo) AS stock_bajo,
                (SELECT COUNT(DISTINCT v.pedido_id) FROM ventas v WHERE DATE(v.created_at) BETWEEN ? AND ?) AS tickets_emitidos",
            [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin]
        );

        $ventasDiarias = DB::table('ventas')
            ->selectRaw('DATE(created_at) as fecha, SUM(monto_total) as total')
            ->whereBetween(DB::raw('DATE(created_at)'), [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get();

        $metodoPago = DB::table('ventas')
            ->selectRaw('metodo_pago, COUNT(*) as cantidad')
            ->whereBetween(DB::raw('DATE(created_at)'), [$fechaInicio, $fechaFin])
            ->groupBy('metodo_pago')
            ->orderByDesc('cantidad')
            ->get();

        $topProductos = DB::table('detalle_pedidos as dp')
            ->join('productos as p', 'p.id', '=', 'dp.producto_id')
            ->join('pedidos as pe', 'pe.id', '=', 'dp.pedido_id')
            ->selectRaw('p.nombre as producto, SUM(dp.cantidad) as cantidad, SUM(dp.subtotal) as revenue')
            ->whereBetween(DB::raw('DATE(pe.created_at)'), [$fechaInicio, $fechaFin])
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('revenue')
            ->limit(7)
            ->get();

        return [
            'cards' => [
                'ventas_periodo' => (int) ($resumen->ventas_periodo ?? 0),
                'total_vendido' => (float) ($resumen->total_vendido ?? 0),
                'pedidos_periodo' => (int) ($resumen->pedidos_periodo ?? 0),
                'pedidos_pendientes' => (int) ($resumen->pedidos_pendientes ?? 0),
                'stock_bajo' => (int) ($resumen->stock_bajo ?? 0),
                'tickets_emitidos' => (int) ($resumen->tickets_emitidos ?? 0),
            ],
            'series' => [
                'ventas_diarias' => $ventasDiarias,
                'metodo_pago' => $metodoPago,
                'top_productos' => $topProductos,
            ],
        ];
    }
}
