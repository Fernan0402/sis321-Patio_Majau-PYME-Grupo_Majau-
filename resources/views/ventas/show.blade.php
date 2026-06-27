@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalle venta #{{ $venta->id }}</h4>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
        <div class="card-body">
            <p><strong>Pedido:</strong> #{{ $venta->pedido_id }}</p>
            <p><strong>Cajero:</strong> {{ $venta->empleado?->nombre_completo }}</p>
            <p><strong>Método:</strong> {{ $venta->metodo_pago }}</p>
            <p><strong>Monto total:</strong> Bs. {{ number_format($venta->monto_total, 2) }}</p>
            <p><strong>Factura:</strong> {{ $venta->factura?->numero_factura ?? 'No generada' }}</p>

            <a href="{{ route('ventas.factura', $venta) }}" class="btn btn-dark">Ver factura</a>
        </div>
    </div>
</div>
@endsection
