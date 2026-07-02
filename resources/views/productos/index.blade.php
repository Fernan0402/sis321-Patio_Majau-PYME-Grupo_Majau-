@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 products-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🍽️ Gestión de Productos</h2>
                <p class="module-subtitle">Administra el menú del restaurante.</p>
            </div>
            <button class="btn btn-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalCrearProducto">
                + Nuevo Producto
            </button>
        </div>

        <div class="module-filter mb-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Categoría</label>
                    <select name="categoria" class="form-select rounded-pill">
                        <option value="">Todas</option>
                        @foreach($categoriasDisponibles as $cat)
                            <option value="{{ $cat }}" @selected($categoria === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select rounded-pill">
                        <option value="">Todos</option>
                        <option value="Activo" @selected(($estado ?? 'Activo') === 'Activo')>Activo</option>
                        <option value="Inactivo" @selected($estado === 'Inactivo')>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100">Filtrar</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('productos.index', ['estado' => 'Activo']) }}" class="btn btn-outline-secondary rounded-pill w-100">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="row g-3">
            @forelse($productos as $producto)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <article class="product-card h-100">
                        <div class="product-card-image">🍲</div>
                        <div class="product-card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h5 class="product-card-title">{{ $producto->nombre }}</h5>
                                <span class="product-card-price">Bs. {{ number_format($producto->precio, 2) }}</span>
                            </div>
                            <p class="product-card-text">{{ $producto->descripcion ?: 'Sin descripción' }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="product-card-tag">{{ $producto->categoria ?: 'Sin categoría' }}</span>
                                <span class="badge {{ $producto->estado === 'Activo' ? 'bg-success' : 'bg-danger' }}">{{ $producto->estado }}</span>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button
                                    class="btn btn-sm btn-outline-dark rounded-pill js-edit-product"
                                    data-id="{{ $producto->id }}"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-descripcion="{{ $producto->descripcion }}"
                                    data-precio="{{ $producto->precio }}"
                                    data-categoria="{{ $producto->categoria }}"
                                    data-estado="{{ $producto->estado }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarProducto"
                                >
                                    Editar
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-danger rounded-pill js-delete-product"
                                    data-id="{{ $producto->id }}"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarProducto"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">No hay productos para los filtros seleccionados.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Mostrando {{ $productos->count() }} productos</small>
            {{ $productos->links() }}
        </div>
    </div>
</div>

{{-- Modal crear producto --}}
<div class="modal fade products-modal" id="modalCrearProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('productos.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del producto</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio (Bs.)</label>
                            <input type="number" step="0.01" min="0" name="precio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach(['Platos Fuertes', 'Sopas', 'Ensaladas', 'Acompañamientos', 'Bebidas', 'Postres'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="estado" value="Activo">
                    </div>
                    <div class="modal-footer px-0 pb-0 mt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal editar producto --}}
<div class="modal fade products-modal" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarProducto">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del producto</label>
                            <input type="text" name="nombre" id="edit_nombre_producto" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion_producto" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio (Bs.)</label>
                            <input type="number" step="0.01" min="0" name="precio" id="edit_precio_producto" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" id="edit_categoria_producto" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach(['Platos Fuertes', 'Sopas', 'Ensaladas', 'Acompañamientos', 'Bebidas', 'Postres'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="edit_estado_producto" class="form-select">
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

{{-- Modal desactivar producto --}}
<div class="modal fade products-modal" id="modalEliminarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">⚠️ Eliminar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-1">¿Estás seguro de eliminar este producto?</p>
                <p class="text-muted mb-3" id="deleteProductName">Producto</p>
                <form method="POST" id="formEliminarProducto" class="d-flex justify-content-center gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editForm = document.getElementById('formEditarProducto');
    const deleteForm = document.getElementById('formEliminarProducto');
    const deleteProductName = document.getElementById('deleteProductName');

    document.querySelectorAll('.js-edit-product').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            editForm.action = `{{ url('productos') }}/${id}`;
            document.getElementById('edit_nombre_producto').value = btn.dataset.nombre ?? '';
            document.getElementById('edit_descripcion_producto').value = btn.dataset.descripcion ?? '';
            document.getElementById('edit_precio_producto').value = btn.dataset.precio ?? 0;
            document.getElementById('edit_categoria_producto').value = btn.dataset.categoria ?? '';
            document.getElementById('edit_estado_producto').value = btn.dataset.estado ?? 'Activo';
        });
    });

    document.querySelectorAll('.js-delete-product').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            deleteForm.action = `{{ url('productos') }}/${id}`;
            deleteProductName.textContent = btn.dataset.nombre ?? 'Producto';
        });
    });
});
</script>
@endsection