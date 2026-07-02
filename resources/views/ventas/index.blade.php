@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 sales-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">💰 Registro de Ventas</h2>
                <p class="module-subtitle">Procesa pagos y genera facturas.</p>
            </div>
            <a href="{{ route('ventas.create') }}" class="btn btn-dark rounded-pill px-4">+ Nueva venta</a>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="module-metric sales-kpi">
                    <p class="module-metric-value">{{ $ventasHoy }}</p>
                    <p class="module-metric-label">Ventas hoy</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="module-metric sales-kpi">
                    <p class="module-metric-value">Bs. {{ number_format($revenueHoy, 2) }}</p>
                    <p class="module-metric-label">Revenue hoy</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="module-metric sales-kpi">
                    <p class="module-metric-value">Bs. {{ number_format($revenueMes, 2) }}</p>
                    <p class="module-metric-label">Revenue mes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="module-metric sales-kpi">
                    <p class="module-metric-value">Bs. {{ number_format($ticketPromedio, 2) }}</p>
                    <p class="module-metric-label">Ticket promedio</p>
                </div>
            </div>
        </div>

        <div class="module-filter mb-3 sales-filter">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control rounded-pill">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control rounded-pill">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-dark w-100 rounded-pill" type="submit">Filtrar</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table sales-table">
                <thead>
                <tr>
                    <th># Venta</th>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($ventas as $venta)
                        <tr>
                            <td><strong>V-{{ str_pad((string) $venta->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>#{{ $venta->pedido_id }}</td>
                            <td>{{ $venta->factura?->razon_social_cliente ?: 'Consumidor Final' }}</td>
                            <td><strong>Bs. {{ number_format($venta->monto_total, 2) }}</strong></td>
                            <td><span class="badge sales-method-badge">{{ $venta->metodo_pago }}</span></td>
                            <td>{{ optional($venta->created_at)->format('d/m H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('ventas.factura', $venta) }}" class="btn btn-sm btn-outline-dark rounded-pill">🧾 Factura</a>
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-secondary rounded-pill">👁</a>
                                </div>
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
        <div class="d-flex justify-content-between align-items-center mt-3 sales-footer flex-wrap gap-2">
            <small class="text-muted">Mostrando {{ $ventas->count() }} ventas</small>
            <small class="text-warning">Total: Bs. {{ number_format($ventas->sum('monto_total'), 2) }}</small>
        </div>
        <div class="mt-3">
            {{ $ventas->links() }}
        </div>
    </div>
</div>
@endsection
