@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 sales-invoice-shell">
        <div class="module-header d-print-none">
            <div>
                <h2 class="module-title">🧾 Factura</h2>
                <p class="module-subtitle">{{ $factura->numero_factura }}</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-dark rounded-pill px-4">🖨 Imprimir</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cerrar</a>
            </div>
        </div>

        <div class="sales-invoice-card">
            <div class="text-center mb-3">
                <h4 class="mb-0">🏛 Patio del <span class="text-warning">Majau</span></h4>
                <small class="text-muted">Av. Mutualista, Santa Cruz · NIT: 123456789</small>
            </div>

            <p class="purchase-detail-item"><span>Factura</span><strong>{{ $factura->numero_factura }}</strong></p>
            <p class="purchase-detail-item"><span>Pedido</span><strong>#{{ $venta->pedido_id }}</strong></p>
            <p class="purchase-detail-item"><span>Cliente</span><strong>{{ $factura->razon_social_cliente ?: 'Consumidor Final' }}</strong></p>
            <p class="purchase-detail-item"><span>Método de pago</span><strong>{{ $venta->metodo_pago }}</strong></p>
            <p class="purchase-detail-item"><span>Fecha</span><strong>{{ optional($factura->fecha_emision)->format('d/m H:i') ?? $factura->created_at->format('d/m H:i') }}</strong></p>

            <div class="module-table-wrap table-responsive mt-3">
                <table class="table table-sm align-middle module-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>P. Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto?->nombre }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td><strong>Bs. {{ number_format($detalle->subtotal, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h4 class="text-end mt-3 purchase-detail-total">TOTAL: Bs. {{ number_format($factura->monto_total, 2) }}</h4>
        </div>
    </div>
</div>
@endsection
