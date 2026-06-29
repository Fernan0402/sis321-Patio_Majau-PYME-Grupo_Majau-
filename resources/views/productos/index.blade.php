@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestión de Productos - Menú</h4>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Producto
                    </a>
                </div>
                <div class="card-body">
                    <!-- Mensajes de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $producto)
                                <tr>
                                    <td>{{ $producto->id }}</td>
                                    <td><strong>{{ $producto->nombre }}</strong></td>
                                    <td>{{ $producto->descripcion ?? 'Sin descripción' }}</td>
                                    <td>Bs. {{ number_format($producto->precio, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $producto->categoria ?? 'Sin categoría' }}</span>
                                    </td>
                                    <td>
                                        @if($producto->estado == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('productos.show', $producto->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('productos.edit', $producto->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('productos.destroy', $producto->id) }}" 
                                                  method="POST" 
                                                  style="display:inline-block"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No hay productos registrados. 
                                        <a href="{{ route('productos.create') }}">Crear el primer producto</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection