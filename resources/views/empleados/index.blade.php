@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 users-shell">
        <div class="module-header mb-3">
            <div>
                <h2 class="module-title">👥 Gestión de Usuarios</h2>
                <p class="module-subtitle">Administra el personal del restaurante y sus permisos.</p>
            </div>
            <button
                type="button"
                class="btn btn-dark rounded-pill px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearUsuario"
            >
                + Nuevo Usuario
            </button>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="module-filter mb-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="form-label">Buscar usuario</label>
                    <input
                        type="text"
                        name="q"
                        class="form-control rounded-pill"
                        placeholder="Buscar por nombre, apellido o usuario"
                        value="{{ $busqueda ?? '' }}"
                    >
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100">Buscar</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary rounded-pill w-100">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="width: 220px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->id }}</td>
                            <td><strong>{{ $empleado->nombre }} {{ $empleado->apellido }}</strong></td>
                            <td>{{ $empleado->usuario }}</td>
                            <td>
                                <span class="badge users-role users-role-{{ \Illuminate\Support\Str::lower($empleado->rol) }}">
                                    {{ $empleado->rol }}
                                </span>
                            </td>
                            <td>
                                <span class="badge users-status {{ $empleado->estado === 'Activo' ? 'users-status-active' : 'users-status-inactive' }}">
                                    {{ $empleado->estado }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-dark rounded-pill js-open-edit"
                                        data-id="{{ $empleado->id }}"
                                        data-nombre="{{ $empleado->nombre }}"
                                        data-apellido="{{ $empleado->apellido }}"
                                        data-usuario="{{ $empleado->usuario }}"
                                        data-rol="{{ $empleado->rol }}"
                                        data-estado="{{ $empleado->estado }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill js-open-delete"
                                        data-id="{{ $empleado->id }}"
                                        data-nombre="{{ $empleado->nombre }} {{ $empleado->apellido }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarUsuario"
                                    >
                                        Desactivar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 users-footer">
            <small class="text-muted">Mostrando {{ $empleados->count() }} usuarios</small>
            {{ $empleados->links() }}
        </div>
    </div>
</div>

{{-- Modal crear --}}
<div class="modal fade users-modal" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✨ Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('empleados.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select" required>
                                @foreach(['Administrador', 'Mesero', 'Cajero', 'Cocinero'] as $rol)
                                    <option value="{{ $rol }}" @selected(old('rol') === $rol)>{{ $rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer px-0 pb-0 mt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal editar --}}
<div class="modal fade users-modal" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarUsuario">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="edit_usuario" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select name="rol" id="edit_rol" class="form-select" required>
                                @foreach(['Administrador', 'Mesero', 'Cajero', 'Cocinero'] as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="edit_estado" class="form-select">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer px-0 pb-0 mt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
<div class="modal fade users-modal" id="modalEliminarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">⚠️ Desactivar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-1">¿Estás seguro de desactivar este usuario?</p>
                <p class="text-muted mb-3" id="deleteUserName">Usuario</p>
                <form method="POST" id="formEliminarUsuario" class="d-flex justify-content-center gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill">Desactivar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editForm = document.getElementById('formEditarUsuario');
    const deleteForm = document.getElementById('formEliminarUsuario');
    const deleteUserName = document.getElementById('deleteUserName');

    document.querySelectorAll('.js-open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            editForm.action = `{{ url('empleados') }}/${id}`;
            document.getElementById('edit_nombre').value = btn.dataset.nombre ?? '';
            document.getElementById('edit_apellido').value = btn.dataset.apellido ?? '';
            document.getElementById('edit_usuario').value = btn.dataset.usuario ?? '';
            document.getElementById('edit_rol').value = btn.dataset.rol ?? 'Mesero';
            document.getElementById('edit_estado').value = btn.dataset.estado ?? 'Activo';
        });
    });

    document.querySelectorAll('.js-open-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            deleteForm.action = `{{ url('empleados') }}/${id}`;
            deleteUserName.textContent = btn.dataset.nombre ?? 'Usuario';
        });
    });
});
</script>
@endsection
