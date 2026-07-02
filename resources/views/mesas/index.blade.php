@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $disponibles = $mesas->where('estado', 'Disponible')->count();
        $ocupadas = $mesas->where('estado', 'Ocupada')->count();
        $reservadas = $mesas->where('estado', 'Reservada')->count();
    @endphp

    <div class="module-shell p-3 p-md-4 tables-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🍽️ Gestión de Mesas</h2>
                <p class="module-subtitle">Visualiza y controla el estado de las mesas.</p>
            </div>
            @if(auth()->user()->rol === 'Administrador')
                <button class="btn btn-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevaMesa">+ Agregar mesa</button>
            @endif
        </div>

        @if(session('success'))
            <div class="tables-toast mb-3">✅ {{ session('success') }}</div>
        @endif

        <div class="row g-3">
            @forelse($mesas as $mesa)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="table-card">
                        <h3>{{ str_pad((string) $mesa->numero_mesa, 2, '0', STR_PAD_LEFT) }}</h3>
                        <p class="table-card-capacity">👥 {{ $mesa->capacidad }} persona(s)</p>
                        <span class="badge table-status {{ $mesa->estado === 'Disponible' ? 'table-status-disponible' : ($mesa->estado === 'Ocupada' ? 'table-status-ocupada' : 'table-status-reservada') }}">
                            {{ $mesa->estado }}
                        </span>

                        <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                            @if($mesa->estado === 'Disponible')
                                <button
                                    class="btn btn-sm rounded-pill table-action table-action-ocupar js-open-status-modal"
                                    data-mesa-id="{{ $mesa->id }}"
                                    data-mesa-numero="{{ $mesa->numero_mesa }}"
                                    data-estado-actual="{{ $mesa->estado }}"
                                    data-estado-nuevo="Ocupada"
                                    data-icono="🍳"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCambiarEstadoMesa"
                                >
                                    Ocupar
                                </button>
                                <button
                                    class="btn btn-sm rounded-pill table-action table-action-reservar js-open-status-modal"
                                    data-mesa-id="{{ $mesa->id }}"
                                    data-mesa-numero="{{ $mesa->numero_mesa }}"
                                    data-estado-actual="{{ $mesa->estado }}"
                                    data-estado-nuevo="Reservada"
                                    data-icono="📋"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCambiarEstadoMesa"
                                >
                                    Reservar
                                </button>
                            @else
                                <button
                                    class="btn btn-sm rounded-pill table-action table-action-liberar js-open-status-modal"
                                    data-mesa-id="{{ $mesa->id }}"
                                    data-mesa-numero="{{ $mesa->numero_mesa }}"
                                    data-estado-actual="{{ $mesa->estado }}"
                                    data-estado-nuevo="Disponible"
                                    data-icono="✅"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCambiarEstadoMesa"
                                >
                                    Liberar
                                </button>
                            @endif

                            @if(auth()->user()->rol === 'Administrador')
                                <button
                                    class="btn btn-sm rounded-pill table-icon-btn js-open-edit-mesa"
                                    data-mesa-id="{{ $mesa->id }}"
                                    data-mesa-numero="{{ $mesa->numero_mesa }}"
                                    data-mesa-capacidad="{{ $mesa->capacidad }}"
                                    data-mesa-estado="{{ $mesa->estado }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarMesa"
                                    title="Editar mesa"
                                >
                                    ✎
                                </button>
                                <button
                                    class="btn btn-sm rounded-pill table-icon-btn table-icon-btn-danger js-open-delete-mesa"
                                    data-mesa-id="{{ $mesa->id }}"
                                    data-mesa-numero="{{ $mesa->numero_mesa }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarMesa"
                                    title="Eliminar mesa"
                                >
                                    🗑
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center text-muted">No hay mesas activas.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 tables-footer flex-wrap gap-2">
            <small class="text-success">● Disponible: {{ $disponibles }}</small>
            <small class="text-warning">● Ocupada: {{ $ocupadas }}</small>
            <small class="text-secondary">● Reservada: {{ $reservadas }}</small>
            <small class="text-muted">Total: {{ method_exists($mesas, 'total') ? $mesas->total() : $mesas->count() }} mesas</small>
        </div>

        <div class="mt-3">
            {{ $mesas->links() }}
        </div>
    </div>
</div>

@if(auth()->user()->rol === 'Administrador')
    {{-- Modal nueva mesa --}}
    <div class="modal fade tables-modal" id="modalNuevaMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Nueva Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('mesas.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Número de mesa</label>
                                <input type="number" min="1" name="numero_mesa" class="form-control" value="{{ old('numero_mesa') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Capacidad</label>
                                <input type="number" min="1" max="20" name="capacidad" class="form-control" value="{{ old('capacidad', 4) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Estado inicial</label>
                                <select name="estado" class="form-select">
                                    <option value="Disponible">Disponible</option>
                                    <option value="Ocupada">Ocupada</option>
                                    <option value="Reservada">Reservada</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4">Crear Mesa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal editar mesa --}}
    <div class="modal fade tables-modal" id="modalEditarMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ Editar Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditarMesa">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Número de mesa</label>
                                <input type="number" min="1" name="numero_mesa" id="editMesaNumero" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Capacidad</label>
                                <input type="number" min="1" max="20" name="capacidad" id="editMesaCapacidad" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Estado</label>
                                <select name="estado" id="editMesaEstado" class="form-select">
                                    <option value="Disponible">Disponible</option>
                                    <option value="Ocupada">Ocupada</option>
                                    <option value="Reservada">Reservada</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal eliminar mesa --}}
    <div class="modal fade tables-modal" id="modalEliminarMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">⚠️ Eliminar Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="tables-modal-icon mb-2">🗑️</div>
                    <p class="mb-1">¿Eliminar la <strong id="deleteMesaNombre">Mesa 0</strong>?</p>
                    <p class="text-muted small">Esta acción no se puede deshacer.</p>
                    <form method="POST" id="formEliminarMesa">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Modal cambiar estado --}}
<div class="modal fade tables-modal" id="modalCambiarEstadoMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🌀 Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="tables-modal-icon" id="estadoMesaIcono">📋</div>
                <p class="mb-1">¿Cambiar estado de la <strong id="estadoMesaNombre">Mesa 0</strong>?</p>
                <p class="mb-1">Estado actual: <strong id="estadoMesaActual">Disponible</strong></p>
                <p>Nuevo estado: <strong id="estadoMesaNuevo">Ocupada</strong></p>
                <form method="POST" id="formCambiarEstadoMesa">
                    @csrf
                    <input type="hidden" name="estado" id="inputEstadoMesaNuevo">
                    <div class="d-flex justify-content-center gap-2 mt-3">
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
    const formEstado = document.getElementById('formCambiarEstadoMesa');
    const inputEstadoNuevo = document.getElementById('inputEstadoMesaNuevo');

    document.querySelectorAll('.js-open-status-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mesaId = btn.dataset.mesaId;
            const mesaNumero = btn.dataset.mesaNumero;
            const estadoActual = btn.dataset.estadoActual;
            const estadoNuevo = btn.dataset.estadoNuevo;
            const icono = btn.dataset.icono || '🌀';

            formEstado.action = `{{ url('mesas') }}/${mesaId}/cambiar-estado`;
            inputEstadoNuevo.value = estadoNuevo;
            document.getElementById('estadoMesaNombre').textContent = `Mesa ${mesaNumero}`;
            document.getElementById('estadoMesaActual').textContent = estadoActual;
            document.getElementById('estadoMesaNuevo').textContent = estadoNuevo;
            document.getElementById('estadoMesaIcono').textContent = icono;
        });
    });

    const formEditar = document.getElementById('formEditarMesa');
    document.querySelectorAll('.js-open-edit-mesa').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mesaId = btn.dataset.mesaId;
            formEditar.action = `{{ url('mesas') }}/${mesaId}`;
            document.getElementById('editMesaNumero').value = btn.dataset.mesaNumero ?? '';
            document.getElementById('editMesaCapacidad').value = btn.dataset.mesaCapacidad ?? '';
            document.getElementById('editMesaEstado').value = btn.dataset.mesaEstado ?? 'Disponible';
        });
    });

    const formEliminar = document.getElementById('formEliminarMesa');
    document.querySelectorAll('.js-open-delete-mesa').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mesaId = btn.dataset.mesaId;
            const mesaNumero = btn.dataset.mesaNumero;
            formEliminar.action = `{{ url('mesas') }}/${mesaId}`;
            document.getElementById('deleteMesaNombre').textContent = `Mesa ${mesaNumero}`;
        });
    });
});
</script>
@endsection
