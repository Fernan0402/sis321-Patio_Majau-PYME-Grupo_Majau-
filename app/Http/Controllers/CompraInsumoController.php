<?php

namespace App\Http\Controllers;

use App\Models\CompraInsumo;
use App\Services\CompraInsumoService;
use Illuminate\Http\Request;

class CompraInsumoController extends Controller
{
    public function __construct(
        private readonly CompraInsumoService $compraInsumoService
    ) {
    }

    /**
     * HU-05: listar compras registradas.
     */
    public function index()
    {
        $compras = $this->compraInsumoService->listarCompras();
        return view('compras_insumos.index', compact('compras'));
    }

    public function create()
    {
        $data = $this->compraInsumoService->datosFormulario();
        return view('compras_insumos.create', $data);
    }

    /**
     * HU-05: registrar compra de insumos y actualizar inventario.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'observacion' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.insumo_id' => 'nullable|exists:insumos,id',
            'items.*.cantidad' => 'nullable|numeric|min:0.01',
            'items.*.precio_unitario' => 'nullable|numeric|min:0.01',
        ]);

        $compra = $this->compraInsumoService->registrarCompra($data, auth()->id());

        return redirect()->route('compras-insumos.show', $compra)
            ->with('success', 'Compra registrada correctamente.');
    }

    public function show(CompraInsumo $compras_insumo)
    {
        $compra = $compras_insumo->load(['proveedor', 'empleado', 'detalles.insumo']);
        return view('compras_insumos.show', compact('compra'));
    }
}
