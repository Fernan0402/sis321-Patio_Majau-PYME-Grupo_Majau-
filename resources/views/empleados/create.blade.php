@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar usuario</h4>
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('empleados.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="usuario" value="{{ old('usuario') }}" class="form-control" required>
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
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
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

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
