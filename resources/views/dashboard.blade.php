@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard - Restaurante Patio del Majau</div>
                <div class="card-body">
                    <div class="mb-3 d-flex gap-2 flex-wrap">
                        <a href="{{ route('empleados.index') }}" class="btn btn-outline-primary">HU-08 Usuarios</a>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">HU-14 Productos</a>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">HU-13 Menú</a>
                        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-primary">HU-02 Pedidos</a>
                        <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary">HU-03 Ventas</a>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header">Pedidos Hoy</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalPedidosHoy }}</h5>
                                    <p class="card-text">Total de pedidos del día</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Ventas Hoy</div>
                                <div class="card-body">
                                    <h5 class="card-title">Bs. {{ number_format($totalVentasHoy, 2) }}</h5>
                                    <p class="card-text">Total facturado hoy</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">Stock Bajo</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $insumosStockBajo }}</h5>
                                    <p class="card-text">Insumos con stock bajo</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-header">Pedidos Pendientes</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pedidosPendientes }}</h5>
                                    <p class="card-text">Pedidos por preparar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($alertasStock->isNotEmpty())
                        <div class="alert alert-warning mt-3">
                            <strong>HU-19: Alerta de stock bajo</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($alertasStock as $insumo)
                                    <li>{{ $insumo->nombre }}: {{ $insumo->stock_actual }} {{ $insumo->unidad_medida }} (mínimo: {{ $insumo->stock_minimo }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection