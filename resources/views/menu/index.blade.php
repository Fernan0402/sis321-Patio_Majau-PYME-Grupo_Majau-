@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $puedeRegistrarPedido = in_array(auth()->user()->rol, ['Administrador', 'Mesero'], true);
    @endphp
    <div class="menu-shell p-3 p-md-4">
        <div class="menu-header text-center mb-4">
            <h1 class="menu-title">Nuestro <span>Menú</span></h1>
            <p class="menu-subtitle">Comida típica cruceña con el mejor sabor tradicional.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">Volver al panel</a>
                @if($puedeRegistrarPedido)
                    <a href="{{ route('pedidos.create') }}" class="btn btn-dark rounded-pill px-4" id="btnIrPedido">
                        Ver pedido <span class="badge text-bg-warning ms-1" id="menuCartCount">0</span>
                    </a>
                @endif
            </div>
        </div>

        @if($productosAgrupados->isNotEmpty())
            <div class="row g-3">
                <div class="{{ $puedeRegistrarPedido ? 'col-lg-8' : 'col-12' }}">
                    <div class="menu-filters mb-4">
                        <button type="button" class="btn btn-sm rounded-pill menu-filter-btn active" data-filter="all">Todos</button>
                        @foreach($productosAgrupados->keys() as $categoria)
                            <button
                                type="button"
                                class="btn btn-sm rounded-pill menu-filter-btn"
                                data-filter="{{ \Illuminate\Support\Str::slug($categoria, '-') }}"
                            >
                                {{ $categoria }}
                            </button>
                        @endforeach
                    </div>

                    <div class="row g-3" id="menuGrid">
                        @foreach($productosAgrupados as $categoria => $items)
                            @foreach($items as $producto)
                                <div
                                    class="col-sm-6 col-xl-4 menu-item"
                                    data-category="{{ \Illuminate\Support\Str::slug($categoria, '-') }}"
                                >
                                    <article class="menu-card h-100">
                                        <div class="menu-card-image">
                                            <span>🍲</span>
                                        </div>
                                        <div class="menu-card-body">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <h5 class="menu-card-title">{{ $producto->nombre }}</h5>
                                                <p class="menu-card-price">Bs. {{ number_format($producto->precio, 2) }}</p>
                                            </div>
                                            <p class="menu-card-text">{{ $producto->descripcion ?: 'Sabor tradicional de la casa.' }}</p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="menu-card-tag">{{ $categoria }}</span>
                                                @if($puedeRegistrarPedido)
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-dark rounded-pill px-3 js-add-to-cart"
                                                        data-id="{{ $producto->id }}"
                                                        data-nombre="{{ $producto->nombre }}"
                                                        data-precio="{{ $producto->precio }}"
                                                    >
                                                        Agregar
                                                    </button>
                                                @else
                                                    <span class="text-muted small">Solo mesero/admin</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                @if($puedeRegistrarPedido)
                    <div class="col-lg-4">
                        <aside class="menu-cart">
                            <div class="menu-cart-header">
                                <h5 class="mb-0">Pedido actual</h5>
                                <span class="badge text-bg-dark" id="menuCartCountSide">0</span>
                            </div>
                            <div class="menu-cart-items" id="menuCartItems">
                                <p class="text-muted small mb-0">Aún no agregaste productos.</p>
                            </div>
                            <div class="menu-cart-footer">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total estimado</span>
                                    <strong id="menuCartTotal">Bs. 0.00</strong>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary w-50 rounded-pill" id="menuCartClear">Vaciar</button>
                                    <a href="{{ route('pedidos.create') }}" class="btn btn-dark w-50 rounded-pill">Registrar pedido</a>
                                </div>
                            </div>
                        </aside>
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-warning">No hay productos activos para mostrar en el menú.</div>
        @endif

        @if($productosPaginados->hasPages())
            <div class="mt-4">
                {{ $productosPaginados->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const CART_KEY = 'majau_cart_v1';
    const puedeRegistrarPedido = @json($puedeRegistrarPedido);
    const filterButtons = document.querySelectorAll('.menu-filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');
    const addButtons = document.querySelectorAll('.js-add-to-cart');
    const cartCount = document.getElementById('menuCartCount');
    const cartCountSide = document.getElementById('menuCartCountSide');
    const cartTotal = document.getElementById('menuCartTotal');
    const cartItemsContainer = document.getElementById('menuCartItems');
    const btnCartClear = document.getElementById('menuCartClear');

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(CART_KEY) || '[]');
        } catch (error) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartBadge();
        renderCart();
    }

    function updateCartBadge() {
        const total = getCart().reduce((sum, item) => sum + Number(item.cantidad || 0), 0);
        if (cartCount) cartCount.textContent = total;
        if (cartCountSide) cartCountSide.textContent = total;
    }

    function addToCart(producto) {
        const cart = getCart();
        const existing = cart.find(item => Number(item.producto_id) === Number(producto.producto_id));
        if (existing) {
            existing.cantidad += 1;
        } else {
            cart.push({ ...producto, cantidad: 1 });
        }
        saveCart(cart);
    }

    function updateQuantity(productId, delta) {
        const cart = getCart();
        const target = cart.find(item => Number(item.producto_id) === Number(productId));
        if (!target) return;
        target.cantidad = Math.max(1, Number(target.cantidad) + delta);
        saveCart(cart);
    }

    function removeFromCart(productId) {
        const cart = getCart().filter(item => Number(item.producto_id) !== Number(productId));
        saveCart(cart);
    }

    function renderCart() {
        if (!cartItemsContainer) return;
        const cart = getCart();
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-muted small mb-0">Aún no agregaste productos.</p>';
            if (cartTotal) cartTotal.textContent = 'Bs. 0.00';
            return;
        }

        let total = 0;
        cartItemsContainer.innerHTML = '';

        cart.forEach(item => {
            const subtotal = Number(item.precio) * Number(item.cantidad);
            total += subtotal;

            const row = document.createElement('div');
            row.className = 'menu-cart-item';
            row.innerHTML = `
                <div class="menu-cart-item-head">
                    <strong>${item.nombre}</strong>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 js-remove" data-id="${item.producto_id}">Quitar</button>
                </div>
                <div class="menu-cart-item-controls">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle js-dec" data-id="${item.producto_id}">-</button>
                    <span>${item.cantidad}</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle js-inc" data-id="${item.producto_id}">+</button>
                    <small>Bs. ${subtotal.toFixed(2)}</small>
                </div>
            `;
            cartItemsContainer.appendChild(row);
        });

        if (cartTotal) cartTotal.textContent = `Bs. ${total.toFixed(2)}`;

        cartItemsContainer.querySelectorAll('.js-inc').forEach(btn => {
            btn.addEventListener('click', () => updateQuantity(btn.dataset.id, 1));
        });
        cartItemsContainer.querySelectorAll('.js-dec').forEach(btn => {
            btn.addEventListener('click', () => updateQuantity(btn.dataset.id, -1));
        });
        cartItemsContainer.querySelectorAll('.js-remove').forEach(btn => {
            btn.addEventListener('click', () => removeFromCart(btn.dataset.id));
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetFilter = button.dataset.filter;

            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            menuItems.forEach(item => {
                const itemCategory = item.dataset.category;
                item.style.display = (targetFilter === 'all' || itemCategory === targetFilter) ? '' : 'none';
            });
        });
    });

    if (puedeRegistrarPedido) {
        addButtons.forEach(button => {
            button.addEventListener('click', () => {
                addToCart({
                    producto_id: Number(button.dataset.id),
                    nombre: button.dataset.nombre,
                    precio: Number(button.dataset.precio),
                });
                button.textContent = 'Agregado';
                setTimeout(() => button.textContent = 'Agregar', 700);
            });
        });
        btnCartClear?.addEventListener('click', () => saveCart([]));
        updateCartBadge();
        renderCart();
    }
});
</script>
@endsection
