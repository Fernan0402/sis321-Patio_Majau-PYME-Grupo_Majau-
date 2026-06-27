@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Ventas</h4>
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">Registrar venta</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Pedido</th>
                        <th>Cajero</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Factura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id }}</td>
                            <td>#{{ $venta->pedido_id }} / Mesa {{ $venta->pedido?->mesa?->numero_mesa ?? '-' }}</td>
                            <td>{{ $venta->empleado?->nombre_completo ?? '-' }}</td>
                            <td>{{ $venta->metodo_pago }}</td>
                            <td>Bs. {{ number_format($venta->monto_total, 2) }}</td>
                            <td>{{ $venta->factura?->numero_factura ?? 'Sin factura' }}</td>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-info">Detalle</a>
                                <a href="{{ route('ventas.factura', $venta) }}" class="btn btn-sm btn-outline-dark">Factura</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
