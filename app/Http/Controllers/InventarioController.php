<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Services\InventarioService;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function __construct(
        private readonly InventarioService $inventarioService
    ) {
    }

    /**
     * HU-19: vista de inventario con alertas de stock bajo.
     */
    public function index()
    {
        $data = $this->inventarioService->obtenerPanelInventario();
        return view('inventario.index', $data);
    }

    /**
     * HU-19: actualización manual de stock.
     */
    public function update(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:20',
        ]);

        $this->inventarioService->actualizarStock($insumo, $data, auth()->id());

        return redirect()->route('inventario.index')
            ->with('success', 'Stock actualizado correctamente.');
    }
}
