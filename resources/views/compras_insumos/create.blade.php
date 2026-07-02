@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 purchase-create-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">🛒 Nueva Compra de Insumos</h2>
                <p class="module-subtitle">Registra el abastecimiento y actualiza inventario automáticamente.</p>
            </div>
            <a href="{{ route('compras-insumos.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                Volver
            </a>
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

        @php
            $oldItems = old('items', [['insumo_id' => '', 'cantidad' => '', 'precio_unitario' => '']]);
            if (count($oldItems) === 0) {
                $oldItems = [['insumo_id' => '', 'cantidad' => '', 'precio_unitario' => '']];
            }
        @endphp

        <form method="POST" action="{{ route('compras-insumos.store') }}" id="purchaseForm">
            @csrf

            <div class="purchase-form-card mb-3">
                <div class="row g-3">
                    <div class="col-lg-7">
                        <label class="form-label">Proveedor</label>
                        <select name="proveedor_id" class="form-select purchase-input" required>
                            <option value="">Seleccionar proveedor...</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" @selected((string) old('proveedor_id') === (string) $proveedor->id)>
                                    {{ $proveedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label">Observación</label>
                        <input
                            type="text"
                            name="observacion"
                            class="form-control purchase-input"
                            maxlength="255"
                            value="{{ old('observacion') }}"
                            placeholder="Detalle opcional de la compra"
                        >
                    </div>
                </div>
            </div>

            <div class="purchase-form-card">
                <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                    <h5 class="mb-0">Productos</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="addItemRow">
                        + Agregar otro producto
                    </button>
                </div>

                <div id="purchaseItems" class="d-grid gap-2">
                    @foreach($oldItems as $index => $item)
                        <div class="row g-2 purchase-item-row" data-row>
                            <div class="col-lg-6">
                                <select name="items[{{ $index }}][insumo_id]" class="form-select purchase-input js-insumo">
                                    <option value="">Seleccionar...</option>
                                    @foreach($insumos as $insumo)
                                        <option value="{{ $insumo->id }}" @selected((string) ($item['insumo_id'] ?? '') === (string) $insumo->id)>
                                            {{ $insumo->nombre }} - {{ $insumo->unidad_medida }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    name="items[{{ $index }}][cantidad]"
                                    class="form-control purchase-input js-cantidad"
                                    placeholder="Cant."
                                    value="{{ $item['cantidad'] ?? '' }}"
                                >
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    name="items[{{ $index }}][precio_unitario]"
                                    class="form-control purchase-input js-precio"
                                    placeholder="P/U"
                                    value="{{ $item['precio_unitario'] ?? '' }}"
                                >
                            </div>
                            <div class="col-lg-1 col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 rounded-pill js-remove-item" title="Quitar">
                                    🗑
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <div class="purchase-total-box">
                        Total: <span id="purchaseTotal">Bs. 0.00</span>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('compras-insumos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Registrar compra</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="purchaseRowTemplate">
    <div class="row g-2 purchase-item-row" data-row>
        <div class="col-lg-6">
            <select class="form-select purchase-input js-insumo" data-name="insumo_id">
                <option value="">Seleccionar...</option>
                @foreach($insumos as $insumo)
                    <option value="{{ $insumo->id }}">{{ $insumo->nombre }} - {{ $insumo->unidad_medida }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <input type="number" step="0.01" min="0.01" class="form-control purchase-input js-cantidad" placeholder="Cant." data-name="cantidad">
        </div>
        <div class="col-lg-3 col-md-6">
            <input type="number" step="0.01" min="0.01" class="form-control purchase-input js-precio" placeholder="P/U" data-name="precio_unitario">
        </div>
        <div class="col-lg-1 col-md-2">
            <button type="button" class="btn btn-outline-danger w-100 rounded-pill js-remove-item" title="Quitar">🗑</button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const itemsContainer = document.getElementById('purchaseItems');
    const addRowBtn = document.getElementById('addItemRow');
    const rowTemplate = document.getElementById('purchaseRowTemplate');
    const totalNode = document.getElementById('purchaseTotal');

    const formatMoney = (value) => `Bs. ${value.toFixed(2)}`;

    const recalculateTotal = () => {
        let total = 0;
        itemsContainer.querySelectorAll('[data-row]').forEach((row) => {
            const cantidad = Number(row.querySelector('.js-cantidad')?.value || 0);
            const precio = Number(row.querySelector('.js-precio')?.value || 0);
            total += cantidad * precio;
        });
        totalNode.textContent = formatMoney(total);
    };

    const renumberRows = () => {
        itemsContainer.querySelectorAll('[data-row]').forEach((row, index) => {
            const insumo = row.querySelector('.js-insumo');
            const cantidad = row.querySelector('.js-cantidad');
            const precio = row.querySelector('.js-precio');

            if (insumo) insumo.name = `items[${index}][insumo_id]`;
            if (cantidad) cantidad.name = `items[${index}][cantidad]`;
            if (precio) precio.name = `items[${index}][precio_unitario]`;
        });
    };

    const bindRowEvents = (row) => {
        row.querySelectorAll('.js-cantidad, .js-precio').forEach((input) => {
            input.addEventListener('input', recalculateTotal);
        });

        const removeBtn = row.querySelector('.js-remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                const rows = itemsContainer.querySelectorAll('[data-row]');
                if (rows.length === 1) {
                    row.querySelectorAll('input, select').forEach((input) => input.value = '');
                } else {
                    row.remove();
                    renumberRows();
                }
                recalculateTotal();
            });
        }
    };

    addRowBtn.addEventListener('click', () => {
        const clone = rowTemplate.content.firstElementChild.cloneNode(true);
        itemsContainer.appendChild(clone);
        bindRowEvents(clone);
        renumberRows();
    });

    itemsContainer.querySelectorAll('[data-row]').forEach(bindRowEvents);
    renumberRows();
    recalculateTotal();
});
</script>
@endsection
