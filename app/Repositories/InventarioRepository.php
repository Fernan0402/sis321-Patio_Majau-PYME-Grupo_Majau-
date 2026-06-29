<?php

namespace App\Repositories;

use App\Models\Insumo;
use App\Models\MovimientoInventario;

class InventarioRepository
{
    public function paginateInsumos(int $perPage = 15)
    {
        return Insumo::where('activo', true)
            ->orderBy('nombre')
            ->paginate($perPage);
    }

    public function movimientosRecientes(int $limit = 20)
    {
        return MovimientoInventario::with(['insumo', 'empleado'])
            ->latest('fecha_hora')
            ->limit($limit)
            ->get();
    }

    public function createMovimiento(array $data): MovimientoInventario
    {
        return MovimientoInventario::create($data);
    }
}
