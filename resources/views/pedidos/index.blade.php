@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Pedidos</h4>
            <a href="{{ route('pedidos.create') }}" class="btn btn-primary">Registrar pedido</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Mesa</th>
                            <th>Mesero</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td>Mesa {{ $pedido->mesa?->numero_mesa ?? '-' }}</td>
                                <td>{{ $pedido->empleado?->nombre_completo ?? '-' }}</td>
                                <td>{{ $pedido->tipo_pedido }}</td>
                                <td>
                                    <form method="POST" action="{{ route('pedidos.cambiarEstado', $pedido) }}" class="d-flex gap-2">
                                        @csrf
                                        <select name="estado" class="form-select form-select-sm">
                                            @foreach(['Pendiente', 'En Preparación', 'Listo', 'Entregado'] as $estado)
                                                <option value="{{ $estado }}" @selected($pedido->estado === $estado)>{{ $estado }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Guardar</button>
                                    </form>
                                </td>
                                <td>Bs. {{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-info">Detalle</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay pedidos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
