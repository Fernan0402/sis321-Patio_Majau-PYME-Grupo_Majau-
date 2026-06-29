@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalle compra #{{ $compra->id }}</h4>
            <a href="{{ route('compras-insumos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
        <div class="card-body">
            <p><strong>Proveedor:</strong> {{ $compra->proveedor?->nombre }}</p>
            <p><strong>Registrado por:</strong> {{ $compra->empleado?->nombre_completo }}</p>
            <p><strong>Fecha:</strong> {{ optional($compra->fecha_hora)->format('d/m/Y H:i') }}</p>
            <p><strong>Observación:</strong> {{ $compra->observacion ?: 'Sin observación' }}</p>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Insumo</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->insumo?->nombre }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h5 class="text-end">Total: Bs. {{ number_format($compra->monto_total, 2) }}</h5>
        </div>
    </div>
</div>
@endsection
