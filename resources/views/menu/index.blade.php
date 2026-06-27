@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Menú del Restaurante</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Volver al panel</a>
    </div>

    @forelse($productos as $categoria => $items)
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <strong>{{ $categoria }}</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $producto)
                                <tr>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>{{ $producto->descripcion ?: 'Sin descripción' }}</td>
                                    <td class="text-end fw-bold">Bs. {{ number_format($producto->precio, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">No hay productos activos para mostrar en el menú.</div>
    @endforelse
</div>
@endsection
