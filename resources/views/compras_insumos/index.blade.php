@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Compras de Insumos</h4>
            <a href="{{ route('compras-insumos.create') }}" class="btn btn-primary">Registrar compra</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Registrado por</th>
                            <th>Monto total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td>{{ $compra->id }}</td>
                                <td>{{ optional($compra->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $compra->proveedor?->nombre }}</td>
                                <td>{{ $compra->empleado?->nombre_completo }}</td>
                                <td>Bs. {{ number_format($compra->monto_total, 2) }}</td>
                                <td>
                                    <a href="{{ route('compras-insumos.show', $compra) }}" class="btn btn-sm btn-info">Detalle</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay compras registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $compras->links() }}
        </div>
    </div>
</div>
@endsection
