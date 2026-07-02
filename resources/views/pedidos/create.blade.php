@extends('layouts.app')

@section('content')
<div class="container">
    @if($insumosStockBajo->isNotEmpty())
        <div class="alert alert-warning orders-alert-stock">
            <strong>⚠️ HU-19:</strong> Hay insumos con stock bajo:
            @foreach($insumosStockBajo as $insumo)
                <span class="badge bg-danger ms-1">{{ $insumo->nombre }} ({{ $insumo->stock_actual }})</span>
            @endforeach
        </div>
    @endif

    <div class="module-shell p-3 p-md-4 orders-create-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">➕ Nuevo Pedido</h2>
                <p class="module-subtitle">Registra una nueva comanda del restaurante.</p>
            </div>
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-dark rounded-pill px-4">Volver</a>
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

        <form method="POST" action="{{ route('pedidos.store') }}">
            @csrf

            <div class="orders-create-card mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mesa</label>
                        <select name="mesa_id" class="form-select orders-input" required>
                            <option value="">Seleccionar mesa...</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}" @selected((string) old('mesa_id') === (string) $mesa->id)>
                                    Mesa {{ $mesa->numero_mesa }} ({{ $mesa->estado }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mesero</label>
                        <select name="empleado_id" class="form-select orders-input" required>
                            <option value="">Seleccionar mesero...</option>
                            @foreach($meseros as $mesero)
                                <option value="{{ $mesero->id }}" @selected((string) old('empleado_id') === (string) $mesero->id)>
                                    {{ $mesero->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de pedido</label>
                        <select name="tipo_pedido" class="form-select orders-input">
                            <option value="Mesa" @selected(old('tipo_pedido', 'Mesa') === 'Mesa')>Mesa</option>
                            <option value="Delivery" @selected(old('tipo_pedido') === 'Delivery')>Delivery</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="orders-create-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Productos</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="btnVaciarCarrito">Vaciar carrito</button>
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill" id="btnAgregarFila">+ Agregar línea</button>
                    </div>
                </div>

                <div class="alert alert-info py-2 rounded-4">
                    Carrito detectado: <strong><span id="cartResumen">0 items</span></strong>.
                    Puedes editar cantidades antes de guardar el pedido.
                </div>
                <div id="itemsContainer"></div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Crear pedido</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const CART_KEY = 'majau_cart_v1';
    const productos = @json($productos->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre, 'precio' => (float) $p->precio])->values());
    const oldItems = @json(old('items', []));
    const itemsContainer = document.getElementById('itemsContainer');
    const cartResumen = document.getElementById('cartResumen');
    const btnAgregarFila = document.getElementById('btnAgregarFila');
    const btnVaciarCarrito = document.getElementById('btnVaciarCarrito');
    let rowIndex = 0;

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(CART_KEY) || '[]');
        } catch (error) {
            return [];
        }
    }

    function setCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateResumen();
    }

    function updateResumen() {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + Number(item.cantidad || 0), 0);
        cartResumen.textContent = `${totalItems} item(s)`;
    }

    function buildProductoOptions(selectedId = '') {
        const opts = ['<option value="">Seleccione...</option>'];
        productos.forEach(p => {
            const selected = Number(selectedId) === Number(p.id) ? 'selected' : '';
            opts.push(`<option value="${p.id}" ${selected}>${p.nombre} - Bs. ${Number(p.precio).toFixed(2)}</option>`);
        });
        return opts.join('');
    }

    function addRow(item = {}) {
        const row = document.createElement('div');
        row.className = 'row g-3 mb-2 align-items-end orders-create-row';
        row.dataset.row = String(rowIndex);
        row.innerHTML = `
            <div class="col-md-7">
                <label class="form-label">Producto ${rowIndex + 1}</label>
                <select name="items[${rowIndex}][producto_id]" class="form-select orders-input">
                    ${buildProductoOptions(item.producto_id)}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" min="1" name="items[${rowIndex}][cantidad]" class="form-control orders-input" value="${Number(item.cantidad || 1)}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger rounded-pill w-100 js-remove-row">Quitar</button>
            </div>
        `;

        row.querySelector('.js-remove-row').addEventListener('click', () => {
            row.remove();
        });

        itemsContainer.appendChild(row);
        rowIndex += 1;
    }

    function loadInitialRows() {
        if (Array.isArray(oldItems) && oldItems.length > 0) {
            oldItems.forEach(item => addRow(item));
            return;
        }

        const cart = getCart();
        if (cart.length > 0) {
            cart.forEach(item => addRow({ producto_id: item.producto_id, cantidad: item.cantidad }));
            return;
        }

        for (let i = 0; i < 3; i += 1) addRow();
    }

    btnAgregarFila.addEventListener('click', () => addRow());

    btnVaciarCarrito.addEventListener('click', () => {
        setCart([]);
        updateResumen();
    });

    document.querySelector('form[action="{{ route('pedidos.store') }}"]')?.addEventListener('submit', () => {
        setCart([]);
    });

    loadInitialRows();
    updateResumen();
});
</script>
@endsection
