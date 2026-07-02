@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 inventory-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">📦 Inventario de Insumos</h2>
                <p class="module-subtitle">Controla el stock de insumos y repón existencias mediante compras registradas.</p>
            </div>
            <a href="{{ route('compras-insumos.create') }}" class="btn btn-dark rounded-pill px-4">
                + Registrar compra
            </a>
        </div>

        <div class="alert alert-info py-2 px-3 mb-3">
            Este módulo permite <strong>consultar/ajustar stock</strong>. Para reabastecimiento formal, use <strong>Registrar compra</strong>.
        </div>

        @php
            $critico = $insumos->first(fn($i) => $i->tieneStockBajo());
        @endphp

        @if($critico)
            <div class="alert alert-warning inventory-alert">
                ⚠️ <strong>Alerta de stock bajo:</strong> El insumo "{{ $critico->nombre }}" ({{ $critico->stock_actual }} {{ $critico->unidad_medida }} / mínimo {{ $critico->stock_minimo }}) está en nivel crítico.
            </div>
        @endif

        <div class="module-table-wrap table-responsive">
            <table class="table table-hover align-middle module-table inventory-table">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Unidad</th>
                        <th>Stock actual</th>
                        <th>Stock mínimo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        <tr class="{{ $insumo->tieneStockBajo() ? 'inventory-row-warning' : '' }}">
                            <td><strong>{{ $insumo->nombre }}</strong></td>
                            <td>{{ $insumo->unidad_medida }}</td>
                            <td>{{ $insumo->stock_actual }}</td>
                            <td>{{ $insumo->stock_minimo }}</td>
                            <td>
                                @if($insumo->tieneStockBajo())
                                    <span class="badge bg-warning text-dark">Stock bajo</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button
                                        class="btn btn-sm btn-outline-dark rounded-pill js-open-edit-insumo"
                                        data-id="{{ $insumo->id }}"
                                        data-nombre="{{ $insumo->nombre }}"
                                        data-unidad="{{ $insumo->unidad_medida }}"
                                        data-stock-actual="{{ $insumo->stock_actual }}"
                                        data-stock-minimo="{{ $insumo->stock_minimo }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarInsumo"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="btn btn-sm btn-warning rounded-pill js-open-abastecer"
                                        data-id="{{ $insumo->id }}"
                                        data-nombre="{{ $insumo->nombre }}"
                                        data-unidad="{{ $insumo->unidad_medida }}"
                                        data-stock-actual="{{ $insumo->stock_actual }}"
                                        data-stock-minimo="{{ $insumo->stock_minimo }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAbastecerInsumo"
                                    >
                                        Abastecer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay insumos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Mostrando {{ $insumos->count() }} insumos</small>
            <small class="text-warning">⚠️ {{ $totalAlertas }} insumo(s) con alerta</small>
        </div>

        <div class="mt-3">
            {{ $insumos->links() }}
        </div>

        <hr class="my-4">
        <h5 class="mb-3">Movimientos recientes</h5>
        <div class="module-table-wrap table-responsive">
            <table class="table table-sm table-hover align-middle module-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Cantidad</th>
                        <th>Stock antes</th>
                        <th>Stock nuevo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $movimiento)
                        <tr>
                            <td>{{ optional($movimiento->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td>{{ $movimiento->insumo?->nombre }}</td>
                            <td><span class="badge text-bg-light">{{ $movimiento->tipo }}</span></td>
                            <td>{{ $movimiento->motivo }}</td>
                            <td>{{ $movimiento->cantidad }}</td>
                            <td>{{ $movimiento->stock_anterior }}</td>
                            <td>{{ $movimiento->stock_nuevo }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Sin movimientos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal editar insumo --}}
<div class="modal fade inventory-modal" id="modalEditarInsumo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Editar Insumo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarInsumo">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del insumo</label>
                            <input type="text" id="edit_nombre_insumo" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unidad de medida</label>
                            <input type="text" name="unidad_medida" id="edit_unidad_insumo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock mínimo</label>
                            <input type="number" min="0" name="stock_minimo" id="edit_stock_minimo_insumo" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Stock actual</label>
                            <input type="number" min="0" name="stock_actual" id="edit_stock_actual_insumo" class="form-control" required>
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

{{-- Modal abastecer insumo --}}
<div class="modal fade inventory-modal" id="modalAbastecerInsumo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📦 Abastecer Insumo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <strong id="abastecer_nombre">Insumo</strong>
                    <div class="text-muted small">Stock actual: <span id="abastecer_actual">0</span></div>
                </div>
                <form method="POST" id="formAbastecerInsumo">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="stock_actual" id="abastecer_stock_actual_hidden">
                    <input type="hidden" name="stock_minimo" id="abastecer_stock_minimo_hidden">
                    <input type="hidden" name="unidad_medida" id="abastecer_unidad_hidden">

                    <label class="form-label text-start w-100">Cantidad a agregar</label>
                    <input type="number" min="1" value="1" class="form-control mb-3" id="abastecer_cantidad" required>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning rounded-pill">Agregar Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editForm = document.getElementById('formEditarInsumo');
    const abastecerForm = document.getElementById('formAbastecerInsumo');

    document.querySelectorAll('.js-open-edit-insumo').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            editForm.action = `{{ url('inventario') }}/${id}`;
            document.getElementById('edit_nombre_insumo').value = btn.dataset.nombre ?? '';
            document.getElementById('edit_unidad_insumo').value = btn.dataset.unidad ?? '';
            document.getElementById('edit_stock_actual_insumo').value = btn.dataset.stockActual ?? 0;
            document.getElementById('edit_stock_minimo_insumo').value = btn.dataset.stockMinimo ?? 0;
        });
    });

    document.querySelectorAll('.js-open-abastecer').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const stockActual = Number(btn.dataset.stockActual ?? 0);
            const stockMinimo = Number(btn.dataset.stockMinimo ?? 0);
            const unidad = btn.dataset.unidad ?? '';

            abastecerForm.action = `{{ url('inventario') }}/${id}`;
            document.getElementById('abastecer_nombre').textContent = btn.dataset.nombre ?? 'Insumo';
            document.getElementById('abastecer_actual').textContent = `${stockActual} ${unidad}`;
            document.getElementById('abastecer_stock_actual_hidden').value = stockActual;
            document.getElementById('abastecer_stock_minimo_hidden').value = stockMinimo;
            document.getElementById('abastecer_unidad_hidden').value = unidad;
            document.getElementById('abastecer_cantidad').value = 1;
        });
    });

    abastecerForm.addEventListener('submit', (event) => {
        const actual = Number(document.getElementById('abastecer_stock_actual_hidden').value || 0);
        const agregar = Number(document.getElementById('abastecer_cantidad').value || 0);
        if (agregar <= 0) {
            event.preventDefault();
            return;
        }
        document.getElementById('abastecer_stock_actual_hidden').value = actual + agregar;
    });
});
</script>
@endsection
