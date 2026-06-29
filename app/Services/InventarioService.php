<?php

namespace App\Services;

use App\Models\Insumo;
use App\Repositories\InventarioRepository;

class InventarioService
{
    public function __construct(
        private readonly InventarioRepository $inventarioRepository
    ) {
    }

    public function obtenerPanelInventario(): array
    {
        $insumos = $this->inventarioRepository->paginateInsumos(20);
        $totalAlertas = Insumo::where('activo', true)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->count();
        $movimientos = $this->inventarioRepository->movimientosRecientes(25);

        return compact('insumos', 'totalAlertas', 'movimientos');
    }

    public function actualizarStock(Insumo $insumo, array $data, ?int $empleadoId = null): void
    {
        $stockAnterior = (float) $insumo->stock_actual;

        $insumo->update([
            'stock_actual' => $data['stock_actual'],
            'stock_minimo' => $data['stock_minimo'],
            'unidad_medida' => $data['unidad_medida'],
        ]);

        $diferencia = (float) $insumo->stock_actual - $stockAnterior;
        if ($diferencia != 0.0) {
            $this->inventarioRepository->createMovimiento([
                'insumo_id' => $insumo->id,
                'empleado_id' => $empleadoId,
                'tipo' => $diferencia > 0 ? 'Entrada' : 'Salida',
                'motivo' => 'Ajuste manual de inventario',
                'cantidad' => abs($diferencia),
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $insumo->stock_actual,
                'referencia_tipo' => 'AjusteManual',
                'referencia_id' => $insumo->id,
            ]);
        }
    }
}
