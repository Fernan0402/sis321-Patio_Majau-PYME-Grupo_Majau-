@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 sales-detail-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🧾 Detalle de Venta</h2>
                <p class="module-subtitle">Venta V-{{ str_pad((string) $venta->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-dark rounded-pill px-4">Volver</a>
        </div>

        <div class="purchase-detail-card mb-3">
            <p class="purchase-detail-item"><span>Pedido</span><strong>#{{ $venta->pedido_id }}</strong></p>
            <p class="purchase-detail-item"><span>Cajero</span><strong>{{ $venta->empleado?->nombre_completo ?? '-' }}</strong></p>
            <p class="purchase-detail-item"><span>Método de pago</span><strong>{{ $venta->metodo_pago }}</strong></p>
            <p class="purchase-detail-item"><span>Cliente</span><strong>{{ $venta->factura?->razon_social_cliente ?: 'Consumidor Final' }}</strong></p>
            <p class="purchase-detail-item"><span>NIT</span><strong>{{ $venta->factura?->nit_cliente ?: 'S/N' }}</strong></p>
            <p class="purchase-detail-item"><span>Factura</span><strong>{{ $venta->factura?->numero_factura ?? 'No generada' }}</strong></p>
            <p class="purchase-detail-item"><span>Total</span><strong class="purchase-detail-total">Bs. {{ number_format($venta->monto_total, 2) }}</strong></p>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('ventas.factura', $venta) }}" class="btn btn-dark rounded-pill px-4">🧾 Ver factura</a>
        </div>
    </div>
</div>
@endsection
