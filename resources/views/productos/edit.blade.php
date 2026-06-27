@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Editar Producto</h4>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary float-end">Volver</a>
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

                    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $producto->nombre) }}" 
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (Bs.) *</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('precio') is-invalid @enderror" 
                                   id="precio" 
                                   name="precio" 
                                   value="{{ old('precio', $producto->precio) }}" 
                                   required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <input type="text" 
                                   class="form-control @error('categoria') is-invalid @enderror" 
                                   id="categoria" 
                                   name="categoria" 
                                   value="{{ old('categoria', $producto->categoria) }}">
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado">
                                <option value="Activo" {{ old('estado', $producto->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado', $producto->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection