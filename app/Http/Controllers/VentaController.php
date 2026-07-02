<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventas = Venta::with(['pedido.mesa', 'empleado', 'factura'])
            ->when($fechaInicio, fn ($q) => $q->whereDate('created_at', '>=', $fechaInicio))
            ->when($fechaFin, fn ($q) => $q->whereDate('created_at', '<=', $fechaFin))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $hoy = now()->toDateString();
        $inicioMes = now()->copy()->startOfMonth()->toDateString();

        $ventasHoy = Venta::whereDate('created_at', $hoy)->count();
        $revenueHoy = (float) Venta::whereDate('created_at', $hoy)->sum('monto_total');
        $revenueMes = (float) Venta::whereDate('created_at', '>=', $inicioMes)->sum('monto_total');
        $ticketPromedio = (float) Venta::query()->avg('monto_total');

        return view('ventas.index', compact(
            'ventas',
            'fechaInicio',
            'fechaFin',
            'ventasHoy',
            'revenueHoy',
            'revenueMes',
            'ticketPromedio'
        ));
    }

    public function create()
    {
        $pedidos = Pedido::where('estado', 'Entregado')
            ->doesntHave('venta')
            ->with('mesa', 'detalles')
            ->orderByDesc('id')
            ->get();

        $usuarioActual = auth()->user();

        return view('ventas.create', compact('pedidos', 'usuarioActual'));
    }

    /**
     * HU-03: Registrar venta
     * HU-17: Generar factura
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'metodo_pago' => 'required|in:Efectivo,Tarjeta Débito,Tarjeta Crédito,QR',
            'razon_social_cliente' => 'nullable|string|max:150',
            'nit_cliente' => 'nullable|string|max:50',
            'generar_factura' => 'nullable|boolean',
        ]);

        $venta = DB::transaction(function () use ($data) {
            $pedido = Pedido::with('mesa')->findOrFail($data['pedido_id']);

            $venta = Venta::create([
                'pedido_id' => $pedido->id,
                // El cajero de la venta siempre es el usuario autenticado.
                'empleado_id' => auth()->id(),
                // HU-03: el monto se calcula automáticamente desde el pedido.
                'monto_total' => $pedido->total,
                'metodo_pago' => $data['metodo_pago'],
            ]);

            // Al cerrar la venta, liberamos la mesa.
            if ($pedido->mesa) {
                $pedido->mesa->update(['estado' => 'Disponible']);
            }
            // HU-03: dejar explícitamente el pedido como entregado.
            $pedido->update(['estado' => 'Entregado']);

            $generarFactura = array_key_exists('generar_factura', $data)
                ? filter_var($data['generar_factura'], FILTER_VALIDATE_BOOL)
                : true;

            if ($generarFactura) {
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
        $venta->loadMissing('factura', 'pedido.mesa', 'pedido.detalles.producto', 'empleado');

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
