@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Factura {{ $factura->numero_factura }}</h4>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
        <div class="card-body">
            <p><strong>Número:</strong> {{ $factura->numero_factura }}</p>
            <p><strong>Fecha:</strong> {{ optional($factura->fecha_emision)->format('d/m/Y H:i') ?? $factura->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Cliente:</strong> {{ $factura->razon_social_cliente ?: 'S/N' }}</p>
            <p><strong>NIT:</strong> {{ $factura->nit_cliente ?: 'S/N' }}</p>
            <hr>
            <p><strong>Venta #{{ $venta->id }}</strong></p>
            <p><strong>Método de pago:</strong> {{ $venta->metodo_pago }}</p>
            <h4 class="text-end">TOTAL: Bs. {{ number_format($factura->monto_total, 2) }}</h4>
        </div>
    </div>
</div>
@endsection
