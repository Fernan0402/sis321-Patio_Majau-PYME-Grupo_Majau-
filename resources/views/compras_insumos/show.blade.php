@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 purchases-detail-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">📋 Detalle de Compra</h2>
                <p class="module-subtitle">Compra C-{{ str_pad((string) $compra->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('compras-insumos.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                Volver
            </a>
        </div>

        <div class="purchase-detail-card mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="purchase-detail-item"><span>Proveedor</span><strong>{{ $compra->proveedor?->nombre }}</strong></p>
                    <p class="purchase-detail-item"><span>Registrado por</span><strong>{{ $compra->empleado?->nombre_completo }}</strong></p>
                    <p class="purchase-detail-item"><span>Fecha</span><strong>{{ optional($compra->fecha_hora)->format('d/m/Y H:i') }}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="purchase-detail-item">
                        <span>Estado</span>
                        <strong><span class="badge purchases-status">Registrada</span></strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Observación</span>
                        <strong>{{ $compra->observacion ?: 'Sin observación' }}</strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Total compra</span>
                        <strong class="purchase-detail-total">Bs. {{ number_format($compra->monto_total, 2) }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table purchases-detail-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compra->detalles as $detalle)
                        <tr>
                            <td><strong>{{ $detalle->insumo?->nombre }}</strong></td>
                            <td>{{ rtrim(rtrim(number_format((float) $detalle->cantidad, 2, '.', ''), '0'), '.') }}</td>
                            <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td><strong>Bs. {{ number_format($detalle->subtotal, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay detalle de productos en esta compra.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <div class="purchase-total-box">Total: Bs. {{ number_format($compra->monto_total, 2) }}</div>
        </div>
    </div>
</div>
@endsection
