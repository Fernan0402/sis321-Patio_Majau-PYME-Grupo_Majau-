<?php

namespace App\Services;

use App\Models\Mesa;
use App\Repositories\MesaRepository;

class MesaService
{
    public function __construct(
        private readonly MesaRepository $mesaRepository
    ) {
    }

    public function listarMesas(int $perPage = 15)
    {
        return $this->mesaRepository->paginateActivas($perPage);
    }

    public function registrarMesa(array $data): Mesa
    {
        return $this->mesaRepository->create([
            'numero_mesa' => $data['numero_mesa'],
            'capacidad' => $data['capacidad'],
            'estado' => $data['estado'] ?? 'Disponible',
            'activo' => true,
        ]);
    }

    public function editarMesa(Mesa $mesa, array $data): bool
    {
        return $this->mesaRepository->update($mesa, [
            'numero_mesa' => $data['numero_mesa'],
            'capacidad' => $data['capacidad'],
            'estado' => $data['estado'],
        ]);
    }

    public function eliminarMesa(Mesa $mesa): bool
    {
        return $this->mesaRepository->update($mesa, [
            'activo' => false,
            'estado' => 'Reservada',
        ]);
    }

    public function cambiarEstado(Mesa $mesa, string $estado): bool
    {
        return $this->mesaRepository->update($mesa, ['estado' => $estado]);
    }
}
