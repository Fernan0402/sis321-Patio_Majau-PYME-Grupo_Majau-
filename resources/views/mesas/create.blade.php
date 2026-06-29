@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar Mesa</h4>
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
            <form method="POST" action="{{ route('mesas.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Número de mesa</label>
                    <input type="number" min="1" name="numero_mesa" class="form-control" value="{{ old('numero_mesa') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacidad</label>
                    <input type="number" min="1" max="20" name="capacidad" class="form-control" value="{{ old('capacidad') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Disponible">Disponible</option>
                        <option value="Ocupada">Ocupada</option>
                        <option value="Reservada">Reservada</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar mesa</button>
            </form>
        </div>
    </div>
</div>
@endsection
