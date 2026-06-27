@extends('layouts.app')

@section('content')
<div class="container">
    @if($insumosStockBajo->isNotEmpty())
        <div class="alert alert-warning">
            <strong>HU-19:</strong> Hay insumos con stock bajo:
            @foreach($insumosStockBajo as $insumo)
                <span class="badge bg-danger ms-1">{{ $insumo->nombre }} ({{ $insumo->stock_actual }})</span>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar pedido</h4>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Volver</a>
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

            <form method="POST" action="{{ route('pedidos.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Mesa</label>
                        <select name="mesa_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}">Mesa {{ $mesa->numero_mesa }} ({{ $mesa->estado }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mesero</label>
                        <select name="empleado_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($meseros as $mesero)
                                <option value="{{ $mesero->id }}">{{ $mesero->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de pedido</label>
                        <select name="tipo_pedido" class="form-select">
                            <option value="Mesa">Mesa</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                    </div>
                </div>

                {{-- Se envían 3 líneas de detalle para simplificar Sprint 1 --}}
                @for($i = 0; $i < 3; $i++)
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">Producto {{ $i + 1 }}</label>
                            <select name="items[{{ $i }}][producto_id]" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }} - Bs. {{ number_format($producto->precio, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cantidad</label>
                            <input type="number" min="1" name="items[{{ $i }}][cantidad]" class="form-control" value="1">
                        </div>
                    </div>
                @endfor

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Guardar pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
