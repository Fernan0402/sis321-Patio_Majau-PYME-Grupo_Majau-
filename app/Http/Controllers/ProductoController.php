<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * HU-14: Registrar productos
     * Lista de productos del menú para gestión interna.
     */
    public function index()
    {
        $productos = Producto::where('activo', true)
            ->orderBy('nombre')
            ->paginate(15);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $insumos = Insumo::orderBy('nombre')->get();
        return view('productos.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo',
            'insumos' => 'nullable|array',
            'insumos.*' => 'exists:insumos,id',
            'cantidades' => 'nullable|array',
            'cantidades.*' => 'nullable|numeric|min:0.01',
        ]);

        $producto = Producto::create($request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria',
            'estado',
        ]));

        // HU-14: Asociación producto-insumo desde UI.
        $syncData = [];
        foreach ((array) $request->input('insumos', []) as $insumoId) {
            $cantidad = (float) ($request->input("cantidades.$insumoId") ?? 0);
            if ($cantidad > 0) {
                $syncData[$insumoId] = ['cantidad_necesaria' => $cantidad];
            }
        }
        if (! empty($syncData)) {
            $producto->insumos()->sync($syncData);
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $insumos = Insumo::orderBy('nombre')->get();
        $insumosAsignados = $producto->insumos->pluck('pivot.cantidad_necesaria', 'id');
        return view('productos.edit', compact('producto', 'insumos', 'insumosAsignados'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo',
            'insumos' => 'nullable|array',
            'insumos.*' => 'exists:insumos,id',
            'cantidades' => 'nullable|array',
            'cantidades.*' => 'nullable|numeric|min:0.01',
        ]);

        $producto->update($request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria',
            'estado',
        ]));

        $syncData = [];
        foreach ((array) $request->input('insumos', []) as $insumoId) {
            $cantidad = (float) ($request->input("cantidades.$insumoId") ?? 0);
            if ($cantidad > 0) {
                $syncData[$insumoId] = ['cantidad_necesaria' => $cantidad];
            }
        }
        $producto->insumos()->sync($syncData);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->update([
            'estado' => 'Inactivo',
            'activo' => false,
        ]);
        return redirect()->route('productos.index')
            ->with('success', 'Producto desactivado correctamente (borrado lógico).');
    }

    /**
     * HU-13: Ver menú
     * Vista pública/simple para clientes con productos activos.
     */
    public function menu()
    {
        $productos = Producto::where('estado', 'Activo')
            ->where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get()
            ->groupBy(fn (Producto $producto) => $producto->categoria ?: 'Sin categoría');

        return view('menu.index', compact('productos'));
    }
}