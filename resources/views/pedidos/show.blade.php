@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalle Pedido #{{ $pedido->id }}</h4>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
        <div class="card-body">
            <p><strong>Mesa:</strong> {{ $pedido->mesa?->numero_mesa }}</p>
            <p><strong>Mesero:</strong> {{ $pedido->empleado?->nombre_completo }}</p>
            <p><strong>Estado:</strong> {{ $pedido->estado }}</p>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto?->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5 class="text-end">Total: Bs. {{ number_format($pedido->total, 2) }}</h5>
        </div>
    </div>
</div>
@endsection
