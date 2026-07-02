@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $badgeMap = [
            'Pendiente' => 'orders-badge-pendiente',
            'En Preparación' => 'orders-badge-preparacion',
            'Listo' => 'orders-badge-listo',
            'Entregado' => 'orders-badge-entregado',
        ];
    @endphp

    <div class="module-shell p-3 p-md-4 orders-detail-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">📄 Detalle del Pedido</h2>
                <p class="module-subtitle">Pedido #{{ $pedido->id }}</p>
            </div>
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-dark rounded-pill px-4">Volver</a>
        </div>

        <div class="purchase-detail-card mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="purchase-detail-item">
                        <span>Mesa</span>
                        <strong>{{ $pedido->mesa?->numero_mesa ? 'Mesa '.$pedido->mesa?->numero_mesa : '-' }}</strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Mesero</span>
                        <strong>{{ $pedido->empleado?->nombre_completo ?? '-' }}</strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Tipo</span>
                        <strong>{{ $pedido->tipo_pedido }}</strong>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="purchase-detail-item">
                        <span>Estado</span>
                        <strong>
                            <span class="badge orders-badge {{ $badgeMap[$pedido->estado] ?? 'orders-badge-pendiente' }}">
                                {{ $pedido->estado }}
                            </span>
                        </strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Fecha</span>
                        <strong>{{ optional($pedido->fecha_hora ?? $pedido->created_at)->format('Y-m-d') }}</strong>
                    </p>
                    <p class="purchase-detail-item">
                        <span>Total</span>
                        <strong class="purchase-detail-total">Bs. {{ number_format($pedido->total, 2) }}</strong>
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
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedido->detalles as $detalle)
                        <tr>
                            <td><strong>{{ $detalle->producto?->nombre }}</strong></td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td><strong>Bs. {{ number_format($detalle->subtotal, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay productos registrados en este pedido.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <div class="purchase-total-box">Total: Bs. {{ number_format($pedido->total, 2) }}</div>
        </div>
    </div>
</div>
@endsection
