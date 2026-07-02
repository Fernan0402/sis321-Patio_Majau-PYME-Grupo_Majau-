@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 purchases-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🧾 Compras de Insumos</h2>
                <p class="module-subtitle">Gestión de abastecimiento de materia prima.</p>
            </div>
            <a href="{{ route('compras-insumos.create') }}" class="btn btn-dark rounded-pill px-4">
                + Nueva compra
            </a>
        </div>

        <div class="alert alert-warning purchases-alert py-2 px-3">
            ⚠️ <strong>Control recomendado:</strong> revise periódicamente las compras recientes para mantener el stock estable.
        </div>

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table purchases-table">
                <thead>
                    <tr>
                        <th># Compra</th>
                        <th>Proveedor</th>
                        <th>Registrado por</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                        <tr>
                            <td><strong>C-{{ str_pad((string) $compra->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $compra->proveedor?->nombre }}</td>
                            <td>{{ $compra->empleado?->nombre_completo }}</td>
                            <td><strong>Bs. {{ number_format($compra->monto_total, 2) }}</strong></td>
                            <td>{{ optional($compra->fecha_hora)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge purchases-status">
                                    Registrada
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('compras-insumos.show', $compra) }}" class="btn btn-sm btn-outline-dark rounded-pill" title="Ver detalle">
                                        👁
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" title="Edición no disponible en esta versión" disabled>
                                        ✎
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminación no disponible en esta versión" disabled>
                                        🗑
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay compras registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 purchases-footer flex-wrap gap-2">
            <small class="text-muted">
                Mostrando {{ $compras->count() }} compra(s)
            </small>
            <small class="text-warning">
                Total página: Bs. {{ number_format($compras->sum('monto_total'), 2) }}
            </small>
        </div>

        <div class="mt-3">
            {{ $compras->links() }}
        </div>
    </div>
</div>
@endsection
