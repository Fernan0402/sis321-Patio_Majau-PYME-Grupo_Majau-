@extends('layouts.app')

@section('content')
<div class="container">
    <div class="module-shell p-3 p-md-4 sales-create-shell">
        <div class="module-header">
            <div>
                <h2 class="module-title">➕ Nueva Venta</h2>
                <p class="module-subtitle">Registra cobro de pedidos entregados.</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-dark rounded-pill px-4">Volver</a>
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

        <div class="sales-form-card">
            <form method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Pedido</label>
                        <select name="pedido_id" class="form-select sales-input" id="pedido_id" required>
                            <option value="">Seleccionar pedido...</option>
                            @foreach($pedidos as $pedido)
                                <option value="{{ $pedido->id }}" data-total="{{ $pedido->total }}" @selected((string) old('pedido_id') === (string) $pedido->id)>
                                    #{{ $pedido->id }} - Mesa {{ $pedido->mesa?->numero_mesa ?? '-' }} - Bs. {{ number_format($pedido->total, 2) }} ({{ $pedido->estado }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" class="form-select sales-input" required>
                            @foreach(['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito', 'QR'] as $metodo)
                                <option value="{{ $metodo }}" @selected(old('metodo_pago', 'Efectivo') === $metodo)>{{ $metodo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Total</label>
                        <input type="text" id="monto_total_preview" class="form-control sales-input" value="Bs. 0.00" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Cajero</label>
                        <input type="text" class="form-control sales-input" value="{{ $usuarioActual?->nombre_completo }} ({{ $usuarioActual?->rol }})" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Razón social (opcional)</label>
                        <input type="text" name="razon_social_cliente" class="form-control sales-input" maxlength="150" value="{{ old('razon_social_cliente') }}" placeholder="Nombre de empresa o persona">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIT (opcional)</label>
                        <input type="text" name="nit_cliente" class="form-control sales-input" maxlength="50" value="{{ old('nit_cliente') }}" placeholder="Número de identificación tributaria">
                    </div>
                </div>

                <div class="sales-total-banner mt-3">
                    Total a pagar: <span id="totalPagarBanner">Bs. 0.00</span>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Registrar venta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pedidoSelect = document.getElementById('pedido_id');
    const montoPreview = document.getElementById('monto_total_preview');
    const totalBanner = document.getElementById('totalPagarBanner');

    const updateTotal = () => {
        const selected = pedidoSelect.options[pedidoSelect.selectedIndex];
        const total = parseFloat(selected?.dataset?.total ?? 0);
        montoPreview.value = `Bs. ${total.toFixed(2)}`;
        totalBanner.textContent = `Bs. ${total.toFixed(2)}`;
    };

    pedidoSelect?.addEventListener('change', updateTotal);
    if (pedidoSelect) updateTotal();
});
</script>
@endsection
