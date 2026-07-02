@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 tables-form-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">➕ Registrar Mesa</h2>
                <p class="module-subtitle">Crea una nueva mesa para la gestión del salón.</p>
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
            <form method="POST" action="{{ route('mesas.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de mesa</label>
                        <input type="number" min="1" name="numero_mesa" class="form-control tables-form-input" value="{{ old('numero_mesa') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Capacidad</label>
                        <input type="number" min="1" max="20" name="capacidad" class="form-control tables-form-input" value="{{ old('capacidad', 4) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Estado inicial</label>
                        <select name="estado" class="form-select tables-form-input">
                            <option value="Disponible" @selected(old('estado', 'Disponible') === 'Disponible')>Disponible</option>
                            <option value="Ocupada" @selected(old('estado') === 'Ocupada')>Ocupada</option>
                            <option value="Reservada" @selected(old('estado') === 'Reservada')>Reservada</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('mesas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Crear mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
