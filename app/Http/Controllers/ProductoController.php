<?php

namespace App\Http\Controllers;

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
        $productos = Producto::orderBy('nombre')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Producto::create($request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria',
            'estado',
        ]));

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $producto->update($request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria',
            'estado',
        ]));

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    /**
     * HU-13: Ver menú
     * Vista pública/simple para clientes con productos activos.
     */
    public function menu()
    {
        $productos = Producto::where('estado', 'Activo')
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get()
            ->groupBy(fn (Producto $producto) => $producto->categoria ?: 'Sin categoría');

        return view('menu.index', compact('productos'));
    }
}