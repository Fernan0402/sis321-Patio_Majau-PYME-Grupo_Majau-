@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar compra de insumos</h4>
            <a href="{{ route('compras-insumos.index') }}" class="btn btn-secondary">Volver</a>
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

            <form method="POST" action="{{ route('compras-insumos.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedor_id" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observación (opcional)</label>
                    <input type="text" name="observacion" class="form-control" maxlength="255">
                </div>

                <h5>Detalle de compra</h5>
                @for($i = 0; $i < 5; $i++)
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <select name="items[{{ $i }}][insumo_id]" class="form-select">
                                <option value="">Insumo...</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad_medida }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" min="0.01" class="form-control" name="items[{{ $i }}][cantidad]" placeholder="Cantidad">
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" min="0.01" class="form-control" name="items[{{ $i }}][precio_unitario]" placeholder="Precio unitario">
                        </div>
                    </div>
                @endfor

                <button type="submit" class="btn btn-primary mt-3">Guardar compra</button>
            </form>
        </div>
    </div>
</div>
@endsection
