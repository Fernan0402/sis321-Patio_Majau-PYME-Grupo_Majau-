@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Inventario de Insumos</h4>
            <span class="badge bg-danger">Alertas: {{ $totalAlertas }}</span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Insumo</th>
                            <th>Unidad</th>
                            <th>Stock actual</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                            <th style="width: 350px;">Actualizar stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insumos as $insumo)
                            <tr class="{{ $insumo->tieneStockBajo() ? 'table-warning' : '' }}">
                                <td>{{ $insumo->nombre }}</td>
                                <td>{{ $insumo->unidad_medida }}</td>
                                <td>{{ $insumo->stock_actual }}</td>
                                <td>{{ $insumo->stock_minimo }}</td>
                                <td>
                                    @if($insumo->tieneStockBajo())
                                        <span class="badge bg-danger">Stock bajo</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('inventario.update', $insumo) }}" class="row g-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-4">
                                            <input type="number" min="0" name="stock_actual" value="{{ $insumo->stock_actual }}" class="form-control" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" min="0" name="stock_minimo" value="{{ $insumo->stock_minimo }}" class="form-control" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="unidad_medida" value="{{ $insumo->unidad_medida }}" class="form-control" required>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Guardar cambios</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay insumos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $insumos->links() }}
            </div>

            <hr class="my-4">
            <h5>Movimientos recientes</h5>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Insumo</th>
                            <th>Tipo</th>
                            <th>Motivo</th>
                            <th>Cantidad</th>
                            <th>Stock antes</th>
                            <th>Stock nuevo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                            <tr>
                                <td>{{ optional($movimiento->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $movimiento->insumo?->nombre }}</td>
                                <td>{{ $movimiento->tipo }}</td>
                                <td>{{ $movimiento->motivo }}</td>
                                <td>{{ $movimiento->cantidad }}</td>
                                <td>{{ $movimiento->stock_anterior }}</td>
                                <td>{{ $movimiento->stock_nuevo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Sin movimientos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
