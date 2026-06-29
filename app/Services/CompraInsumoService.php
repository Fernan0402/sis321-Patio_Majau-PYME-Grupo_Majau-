<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\Proveedor;
use App\Repositories\CompraInsumoRepository;
use App\Repositories\InventarioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompraInsumoService
{
    public function __construct(
        private readonly CompraInsumoRepository $compraRepository,
        private readonly InventarioRepository $inventarioRepository
    ) {
    }

    public function listarCompras()
    {
        return $this->compraRepository->paginateCompras(15);
    }

    public function datosFormulario(): array
    {
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $insumos = Insumo::where('activo', true)->orderBy('nombre')->get();

        return compact('proveedores', 'insumos');
    }

    public function registrarCompra(array $data, int $empleadoId)
    {
        $items = collect($data['items'] ?? [])
            ->filter(fn ($item) => ! empty($item['insumo_id']) && ! empty($item['cantidad']) && ! empty($item['precio_unitario']))
            ->values();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe registrar al menos un insumo en la compra.',
            ]);
        }

        return DB::transaction(function () use ($data, $items, $empleadoId) {
            $compra = $this->compraRepository->createCompra([
                'proveedor_id' => $data['proveedor_id'],
                'empleado_id' => $empleadoId,
                'monto_total' => 0,
                'observacion' => $data['observacion'] ?? null,
                'activo' => true,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $insumo = Insumo::findOrFail($item['insumo_id']);
                $cantidad = (float) $item['cantidad'];
                $precioUnitario = (float) $item['precio_unitario'];
                $subtotal = $cantidad * $precioUnitario;
                $total += $subtotal;

                $this->compraRepository->createDetalle([
                    'compra_insumo_id' => $compra->id,
                    'insumo_id' => $insumo->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);

                $stockAnterior = (float) $insumo->stock_actual;
                $insumo->increment('stock_actual', $cantidad);

                $this->inventarioRepository->createMovimiento([
                    'insumo_id' => $insumo->id,
                    'empleado_id' => $empleadoId,
                    'tipo' => 'Entrada',
                    'motivo' => 'Compra de insumos',
                    'cantidad' => $cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $insumo->fresh()->stock_actual,
                    'referencia_tipo' => 'CompraInsumo',
                    'referencia_id' => $compra->id,
                ]);
            }

            $compra->update(['monto_total' => $total]);

            return $compra;
        });
    }
}
