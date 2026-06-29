@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Gestión de Mesas</h4>
            @if(auth()->user()->rol === 'Administrador')
                <a href="{{ route('mesas.create') }}" class="btn btn-primary">Registrar mesa</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th># Mesa</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th style="width: 320px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mesas as $mesa)
                            <tr>
                                <td>{{ $mesa->numero_mesa }}</td>
                                <td>{{ $mesa->capacidad }}</td>
                                <td>
                                    <span class="badge bg-{{ $mesa->estado === 'Disponible' ? 'success' : ($mesa->estado === 'Ocupada' ? 'warning' : 'secondary') }}">
                                        {{ $mesa->estado }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('mesas.cambiarEstado', $mesa) }}" method="POST" class="d-inline-flex gap-2">
                                        @csrf
                                        <select name="estado" class="form-select form-select-sm">
                                            @foreach(['Disponible', 'Ocupada', 'Reservada'] as $estado)
                                                <option value="{{ $estado }}" @selected($mesa->estado === $estado)>{{ $estado }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Estado</button>
                                    </form>
                                    @if(auth()->user()->rol === 'Administrador')
                                        <a href="{{ route('mesas.edit', $mesa) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar mesa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay mesas activas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $mesas->links() }}
        </div>
    </div>
</div>
@endsection
