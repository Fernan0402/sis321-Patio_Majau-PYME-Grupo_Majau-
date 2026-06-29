<?php

namespace App\Repositories;

use App\Models\Mesa;

class MesaRepository
{
    public function paginateActivas(int $perPage = 15)
    {
        return Mesa::where('activo', true)
            ->orderBy('numero_mesa')
            ->paginate($perPage);
    }

    public function create(array $data): Mesa
    {
        return Mesa::create($data);
    }

    public function update(Mesa $mesa, array $data): bool
    {
        return $mesa->update($data);
    }
}
