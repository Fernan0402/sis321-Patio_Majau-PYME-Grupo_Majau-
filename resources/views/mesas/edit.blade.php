@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 tables-form-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">✏️ Editar Mesa</h2>
                <p class="module-subtitle">Actualiza configuración de la mesa {{ $mesa->numero_mesa }}.</p>
            </div>
            <a href="{{ route('mesas.index') }}" class="btn btn-outline-dark rounded-pill px-4">Volver</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tables-form-card">
            <form method="POST" action="{{ route('mesas.update', $mesa) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de mesa</label>
                        <input type="number" min="1" name="numero_mesa" class="form-control tables-form-input" value="{{ old('numero_mesa', $mesa->numero_mesa) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Capacidad</label>
                        <input type="number" min="1" max="20" name="capacidad" class="form-control tables-form-input" value="{{ old('capacidad', $mesa->capacidad) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select tables-form-input">
                            @foreach(['Disponible', 'Ocupada', 'Reservada'] as $estado)
                                <option value="{{ $estado }}" @selected(old('estado', $mesa->estado) === $estado)>{{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('mesas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Actualizar mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
