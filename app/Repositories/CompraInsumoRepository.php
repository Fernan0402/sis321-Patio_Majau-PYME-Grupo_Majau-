<?php

namespace App\Repositories;

use App\Models\CompraInsumo;
use App\Models\DetalleCompraInsumo;

class CompraInsumoRepository
{
    public function paginateCompras(int $perPage = 15)
    {
        return CompraInsumo::with(['proveedor', 'empleado'])
            ->where('activo', true)
            ->latest('fecha_hora')
            ->paginate($perPage);
    }

    public function createCompra(array $data): CompraInsumo
    {
        return CompraInsumo::create($data);
    }

    public function createDetalle(array $data): DetalleCompraInsumo
    {
        return DetalleCompraInsumo::create($data);
    }
}
