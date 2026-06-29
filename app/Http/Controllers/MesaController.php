<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Services\MesaService;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function __construct(
        private readonly MesaService $mesaService
    ) {
    }

    /**
     * HU-11: listar mesas con paginación.
     */
    public function index()
    {
        $mesas = $this->mesaService->listarMesas(15);
        return view('mesas.index', compact('mesas'));
    }

    public function create()
    {
        return view('mesas.create');
    }

    /**
     * HU-11: registrar mesa.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_mesa' => 'required|integer|min:1|unique:mesas,numero_mesa',
            'capacidad' => 'required|integer|min:1|max:20',
            'estado' => 'required|in:Disponible,Ocupada,Reservada',
        ]);

        $this->mesaService->registrarMesa($data);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa registrada correctamente.');
    }

    public function edit(Mesa $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    public function update(Request $request, Mesa $mesa)
    {
        $data = $request->validate([
            'numero_mesa' => 'required|integer|min:1|unique:mesas,numero_mesa,' . $mesa->id,
            'capacidad' => 'required|integer|min:1|max:20',
            'estado' => 'required|in:Disponible,Ocupada,Reservada',
        ]);

        $this->mesaService->editarMesa($mesa, $data);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada correctamente.');
    }

    /**
     * HU-11: borrado lógico de mesa.
     */
    public function destroy(Mesa $mesa)
    {
        $this->mesaService->eliminarMesa($mesa);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa desactivada correctamente (borrado lógico).');
    }

    /**
     * HU-12: cambiar estado de mesa (mesero/admin).
     */
    public function cambiarEstado(Request $request, Mesa $mesa)
    {
        $data = $request->validate([
            'estado' => 'required|in:Disponible,Ocupada,Reservada',
        ]);

        $this->mesaService->cambiarEstado($mesa, $data['estado']);

        return redirect()->route('mesas.index')
            ->with('success', 'Estado de mesa actualizado.');
    }
}
