@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detalle del Producto</h4>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary float-end">Volver</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ID:</div>
                        <div class="col-md-8">{{ $producto->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nombre:</div>
                        <div class="col-md-8">{{ $producto->nombre }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Descripción:</div>
                        <div class="col-md-8">{{ $producto->descripcion ?? 'Sin descripción' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Precio:</div>
                        <div class="col-md-8">Bs. {{ number_format($producto->precio, 2) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Categoría:</div>
                        <div class="col-md-8">{{ $producto->categoria ?? 'Sin categoría' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Estado:</div>
                        <div class="col-md-8">
                            @if($producto->estado == 'Activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Creado:</div>
                        <div class="col-md-8">{{ $producto->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Última Actualización:</div>
                        <div class="col-md-8">{{ $producto->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection