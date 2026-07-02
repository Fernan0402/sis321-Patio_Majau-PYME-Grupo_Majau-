@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $estadoActual = request('estado', 'Todos');
        $mesaActual = request('mesa_id', 'Todas');
        $fechaActual = request('fecha', now()->format('Y-m-d'));
        $estadosDisponibles = ['Pendiente', 'En Preparación', 'Listo', 'Entregado'];

        $badgeMap = [
            'Pendiente' => 'orders-badge-pendiente',
            'En Preparación' => 'orders-badge-preparacion',
            'Listo' => 'orders-badge-listo',
            'Entregado' => 'orders-badge-entregado',
        ];

        $accionMap = [
            'Pendiente' => ['label' => 'Cocinar', 'next' => 'En Preparación', 'class' => 'orders-action-cocinar', 'icon' => '🔥'],
            'En Preparación' => ['label' => 'Listo', 'next' => 'Listo', 'class' => 'orders-action-listo', 'icon' => '✅'],
            'Listo' => ['label' => 'Entregar', 'next' => 'Entregado', 'class' => 'orders-action-entregar', 'icon' => '📦'],
            'Entregado' => ['label' => 'Completado', 'next' => null, 'class' => 'orders-action-completado', 'icon' => '✔️'],
        ];
    @endphp

    <div class="module-shell p-3 p-md-4 orders-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🧾 Gestión de Pedidos</h2>
                <p class="module-subtitle">Registro y seguimiento de comandas.</p>
            </div>
            <a href="{{ route('pedidos.create') }}" class="btn btn-dark rounded-pill px-4">+ Nuevo pedido</a>
        </div>

        @if(session('success'))
            <div class="orders-toast mb-3">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('pedidos.index') }}" class="module-filter mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select rounded-pill">
                        <option value="Todos" @selected($estadoActual === 'Todos')>Todos</option>
                        @foreach($estadosDisponibles as $estado)
                            <option value="{{ $estado }}" @selected($estadoActual === $estado)>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mesa</label>
                    <select name="mesa_id" class="form-select rounded-pill">
                        <option value="Todas" @selected($mesaActual === 'Todas')>Todas</option>
                        @foreach($mesas as $mesa)
                            <option value="{{ $mesa->id }}" @selected((string) $mesaActual === (string) $mesa->id)>
                                Mesa {{ $mesa->numero_mesa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fechaActual }}" class="form-control rounded-pill">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table orders-table">
                <thead>
                    <tr>
                        <th># Pedido</th>
                        <th>Mesa</th>
                        <th>Mesero</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        @php
                            $accion = $accionMap[$pedido->estado] ?? $accionMap['Entregado'];
                            $productos = $pedido->detalles->map(function ($detalle) {
                                return ($detalle->producto?->nombre ?? 'Producto') . ' x' . (int) $detalle->cantidad;
                            })->implode(', ');
                        @endphp
                            <tr>
                                <td><strong>#{{ $pedido->id }}</strong></td>
                                <td>Mesa {{ $pedido->mesa?->numero_mesa ?? '-' }}</td>
                                <td>{{ $pedido->empleado?->nombre_completo ?? '-' }}</td>
                                <td>
                                    <span class="orders-products-text">{{ $productos ?: 'Sin productos' }}</span>
                                </td>
                                <td><strong>Bs. {{ number_format($pedido->total, 2) }}</strong></td>
                                <td>
                                    <span class="badge orders-badge {{ $badgeMap[$pedido->estado] ?? 'orders-badge-pendiente' }}">
                                        {{ $pedido->estado }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($accion['next'])
                                            <button
                                                type="button"
                                                class="btn btn-sm rounded-pill orders-action-btn {{ $accion['class'] }} js-open-change-status"
                                                data-pedido-id="{{ $pedido->id }}"
                                                data-estado-actual="{{ $pedido->estado }}"
                                                data-estado-nuevo="{{ $accion['next'] }}"
                                                data-estado-icono="{{ $accion['icon'] }}"
                                                data-accion-label="{{ $accion['label'] }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCambiarEstadoPedido"
                                            >
                                                {{ $accion['label'] }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm rounded-pill orders-action-btn orders-action-completado" disabled>
                                                Completado
                                            </button>
                                        @endif

                                        <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-dark rounded-pill" title="Ver detalle">👁</a>
                                    </div>
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

        <div class="d-flex justify-content-between align-items-center mt-3 orders-footer flex-wrap gap-2">
            <small class="text-muted">Mostrando {{ $pedidos->count() }} pedido(s)</small>
            <small class="text-warning">⏳ {{ $pedidos->whereIn('estado', ['Pendiente', 'En Preparación', 'Listo'])->count() }} en proceso</small>
        </div>

        <div class="mt-3">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>

{{-- Modal cambio de estado --}}
<div class="modal fade orders-modal" id="modalCambiarEstadoPedido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🔄 Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="orders-modal-icon" id="estadoIcono">🔥</div>
                <p class="mb-1">¿Cambiar estado del pedido <strong id="modalPedidoId">#0</strong>?</p>
                <p class="mb-1">Estado actual: <strong id="modalEstadoActual">Pendiente</strong></p>
                <p class="mb-3">Nuevo estado: <strong id="modalEstadoNuevo">En Preparación</strong></p>

                <form method="POST" id="formCambiarEstadoPedido">
                    @csrf
                    <input type="hidden" name="estado" id="inputEstadoNuevo">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formCambiarEstadoPedido');
    const estadoInput = document.getElementById('inputEstadoNuevo');

    document.querySelectorAll('.js-open-change-status').forEach((btn) => {
        btn.addEventListener('click', () => {
            const pedidoId = btn.dataset.pedidoId;
            const estadoActual = btn.dataset.estadoActual;
            const estadoNuevo = btn.dataset.estadoNuevo;
            const icono = btn.dataset.estadoIcono || '🔄';

            form.action = `{{ url('pedidos') }}/${pedidoId}/cambiar-estado`;
            estadoInput.value = estadoNuevo;
            document.getElementById('modalPedidoId').textContent = `#${pedidoId}`;
            document.getElementById('modalEstadoActual').textContent = estadoActual;
            document.getElementById('modalEstadoNuevo').textContent = estadoNuevo;
            document.getElementById('estadoIcono').textContent = icono;
        });
    });
});
</script>
@endsection
