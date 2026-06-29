@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Mesa</h4>
            <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Volver</a>
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
            <form method="POST" action="{{ route('mesas.update', $mesa) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Número de mesa</label>
                    <input type="number" min="1" name="numero_mesa" class="form-control" value="{{ old('numero_mesa', $mesa->numero_mesa) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacidad</label>
                    <input type="number" min="1" max="20" name="capacidad" class="form-control" value="{{ old('capacidad', $mesa->capacidad) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        @foreach(['Disponible', 'Ocupada', 'Reservada'] as $estado)
                            <option value="{{ $estado }}" @selected(old('estado', $mesa->estado) === $estado)>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar mesa</button>
            </form>
        </div>
    </div>
</div>
@endsection
