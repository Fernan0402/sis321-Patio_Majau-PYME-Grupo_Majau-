<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    /**
     * HU-02: Listado de pedidos.
     */
    public function index()
    {
        $pedidos = Pedido::with(['mesa', 'empleado', 'detalles.producto'])
            ->latest()
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $mesas = Mesa::orderBy('numero_mesa')->get();
        $meseros = Empleado::whereIn('rol', ['Mesero', 'Administrador'])
            ->where('activo', true)
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();
        $productos = Producto::where('estado', 'Activo')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
        $insumosStockBajo = Insumo::where('activo', true)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->get();

        return view('pedidos.create', compact('mesas', 'meseros', 'productos', 'insumosStockBajo'));
    }

    /**
     * HU-02: Registrar pedido + verificar stock (HU-19).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mesa_id' => 'required|exists:mesas,id',
            'empleado_id' => 'required|exists:empleados,id',
            'tipo_pedido' => 'required|in:Mesa,Delivery',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'nullable|exists:productos,id',
            'items.*.cantidad' => 'nullable|integer|min:1',
        ]);

        $items = collect($data['items'])
            ->filter(fn ($item) => ! empty($item['producto_id']) && ! empty($item['cantidad']))
            ->values();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe registrar al menos un producto en el pedido.',
            ]);
        }

        DB::transaction(function () use ($data, $items) {
            $pedido = Pedido::create([
                'mesa_id' => $data['mesa_id'],
                'empleado_id' => $data['empleado_id'],
                'tipo_pedido' => $data['tipo_pedido'],
                'estado' => 'Pendiente',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $producto = Producto::with('insumos')->findOrFail($item['producto_id']);
                $cantidad = (int) $item['cantidad'];

                // Verifica disponibilidad por receta (producto_insumo).
                foreach ($producto->insumos as $insumo) {
                    $necesario = (float) $insumo->pivot->cantidad_necesaria * $cantidad;
                    if ($insumo->stock_actual < $necesario) {
                        throw ValidationException::withMessages([
                            'items' => "Stock insuficiente para {$producto->nombre} (insumo: {$insumo->nombre}).",
                        ]);
                    }
                }

                // Descuenta stock de insumos usados por el producto.
                foreach ($producto->insumos as $insumo) {
                    $necesario = (float) $insumo->pivot->cantidad_necesaria * $cantidad;
                    $insumo->decrement('stock_actual', (int) ceil($necesario));
                }

                $subtotal = $cantidad * (float) $producto->precio;
                $total += $subtotal;

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ]);
            }

            $pedido->update(['total' => $total]);
            $pedido->mesa()->update(['estado' => 'Ocupada']);
        });

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido registrado correctamente.');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['mesa', 'empleado', 'detalles.producto']);
        return view('pedidos.show', compact('pedido'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'estado' => 'required|in:Pendiente,En Preparación,Listo,Entregado',
        ]);

        $pedido->update(['estado' => $data['estado']]);

        return redirect()->route('pedidos.index')
            ->with('success', 'Estado del pedido actualizado.');
    }
}
