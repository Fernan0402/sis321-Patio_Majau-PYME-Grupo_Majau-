@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar venta</h4>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
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

            <form method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pedido entregado</label>
                        <select name="pedido_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($pedidos as $pedido)
                                <option value="{{ $pedido->id }}">
                                    Pedido #{{ $pedido->id }} / Mesa {{ $pedido->mesa?->numero_mesa ?? '-' }} / Bs. {{ number_format($pedido->total, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cajero</label>
                        <select name="empleado_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($cajeros as $cajero)
                                <option value="{{ $cajero->id }}">{{ $cajero->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monto total</label>
                        <input type="number" step="0.01" min="0" name="monto_total" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" class="form-select" required>
                            @foreach(['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito', 'QR'] as $metodo)
                                <option value="{{ $metodo }}">{{ $metodo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked value="1" id="generar_factura" name="generar_factura">
                            <label class="form-check-label" for="generar_factura">
                                Generar factura automática
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Razón social (opcional)</label>
                        <input type="text" name="razon_social_cliente" class="form-control" maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIT (opcional)</label>
                        <input type="text" name="nit_cliente" class="form-control" maxlength="50">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar venta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
