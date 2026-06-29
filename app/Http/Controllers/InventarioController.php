<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * HU-19: vista de inventario con alertas de stock bajo.
     */
    public function index()
    {
        $insumos = Insumo::orderBy('nombre')->get();
        $totalAlertas = $insumos->filter(fn (Insumo $insumo) => $insumo->tieneStockBajo())->count();

        return view('inventario.index', compact('insumos', 'totalAlertas'));
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

        $insumo->update($data);

        return redirect()->route('inventario.index')
            ->with('success', 'Stock actualizado correctamente.');
    }
}
