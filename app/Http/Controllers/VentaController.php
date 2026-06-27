<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Factura;
use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * HU-03: Listado de ventas.
     */
    public function index()
    {
        $ventas = Venta::with(['pedido.mesa', 'empleado', 'factura'])
            ->latest()
            ->get();

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $pedidos = Pedido::where('estado', 'Entregado')
            ->doesntHave('venta')
            ->with('mesa')
            ->orderByDesc('id')
            ->get();

        $cajeros = Empleado::whereIn('rol', ['Cajero', 'Administrador'])
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();

        return view('ventas.create', compact('pedidos', 'cajeros'));
    }

    /**
     * HU-03: Registrar venta
     * HU-17: Generar factura
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'empleado_id' => 'required|exists:empleados,id',
            'monto_total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:Efectivo,Tarjeta Débito,Tarjeta Crédito,QR',
            'razon_social_cliente' => 'nullable|string|max:150',
            'nit_cliente' => 'nullable|string|max:50',
            'generar_factura' => 'nullable|boolean',
        ]);

        $venta = DB::transaction(function () use ($data) {
            $pedido = Pedido::with('mesa')->findOrFail($data['pedido_id']);

            $venta = Venta::create([
                'pedido_id' => $pedido->id,
                'empleado_id' => $data['empleado_id'],
                'monto_total' => $data['monto_total'],
                'metodo_pago' => $data['metodo_pago'],
            ]);

            // Al cerrar la venta, liberamos la mesa.
            if ($pedido->mesa) {
                $pedido->mesa->update(['estado' => 'Disponible']);
            }

            if (($data['generar_factura'] ?? true) === true) {
                Factura::create([
                    'venta_id' => $venta->id,
                    'numero_factura' => $this->generarNumeroFactura(),
                    'razon_social_cliente' => $data['razon_social_cliente'] ?? null,
                    'nit_cliente' => $data['nit_cliente'] ?? null,
                    'monto_total' => $venta->monto_total,
                ]);
            }

            return $venta;
        });

        return redirect()->route('ventas.show', $venta)
            ->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load(['pedido.mesa', 'empleado', 'factura']);
        return view('ventas.show', compact('venta'));
    }

    public function generarFactura(Venta $venta)
    {
        $venta->loadMissing('factura', 'pedido.mesa', 'empleado');

        if (! $venta->factura) {
            $venta->factura()->create([
                'numero_factura' => $this->generarNumeroFactura(),
                'monto_total' => $venta->monto_total,
            ]);
            $venta->refresh()->load('factura');
        }

        return view('ventas.factura', ['venta' => $venta, 'factura' => $venta->factura]);
    }

    private function generarNumeroFactura(): string
    {
        $prefix = now()->format('Ymd');
        $correlativo = str_pad((string) (Factura::count() + 1), 6, '0', STR_PAD_LEFT);
        return "F-{$prefix}-{$correlativo}";
    }
}
