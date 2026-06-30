@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard - Restaurante Patio del Majau</div>
                <div class="card-body">
                    <div class="mb-3 d-flex gap-2 flex-wrap">
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">HU-13 Menú</a>
                        @if(auth()->user()->rol === 'Administrador')
                            <a href="{{ route('empleados.index') }}" class="btn btn-outline-primary">HU-08 Usuarios</a>
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">HU-14 Productos</a>
                            <a href="{{ route('inventario.index') }}" class="btn btn-outline-primary">HU-19 Inventario</a>
                            <a href="{{ route('compras-insumos.index') }}" class="btn btn-outline-primary">HU-05 Compras</a>
                        @endif
                        @if(in_array(auth()->user()->rol, ['Administrador', 'Mesero'], true))
                            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-primary">HU-02 Pedidos</a>
                        @endif
                        @if(in_array(auth()->user()->rol, ['Administrador', 'Cajero'], true))
                            <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary">HU-03 Ventas</a>
                        @endif
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <form id="filtroDashboard" class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" value="{{ $fechaInicio }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha fin</label>
                                    <input type="date" class="form-control" id="fecha_fin" value="{{ $fechaFin }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" id="btnFiltrar" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header">Ventas (Periodo)</div>
                                <div class="card-body">
                                    <h5 class="card-title" id="kpi_ventas_periodo">{{ $kpis['cards']['ventas_periodo'] }}</h5>
                                    <p class="card-text">Total de ventas registradas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Total Vendido</div>
                                <div class="card-body">
                                    <h5 class="card-title" id="kpi_total_vendido">Bs. {{ number_format($kpis['cards']['total_vendido'], 2) }}</h5>
                                    <p class="card-text">Monto facturado en periodo</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">Stock Bajo</div>
                                <div class="card-body">
                                    <h5 class="card-title" id="kpi_stock_bajo">{{ $kpis['cards']['stock_bajo'] }}</h5>
                                    <p class="card-text">Insumos con stock bajo</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-header">Pedidos Pendientes</div>
                                <div class="card-body">
                                    <h5 class="card-title" id="kpi_pedidos_pendientes">{{ $kpis['cards']['pedidos_pendientes'] }}</h5>
                                    <p class="card-text">Pedidos por preparar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-info mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Pedidos (Periodo)</h6>
                                    <h4 id="kpi_pedidos_periodo">{{ $kpis['cards']['pedidos_periodo'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Tickets emitidos</h6>
                                    <h4 id="kpi_tickets">{{ $kpis['cards']['tickets_emitidos'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header">Ventas diarias (HU-06)</div>
                                <div class="card-body">
                                    <canvas id="chartVentasDiarias"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header">Métodos de pago</div>
                                <div class="card-body">
                                    <canvas id="chartMetodoPago"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">Top productos por ingresos</div>
                                <div class="card-body">
                                    <canvas id="chartTopProductos"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const kpisIniciales = @json($kpis);
let chartVentasDiarias;
let chartMetodoPago;
let chartTopProductos;

function buildCharts(data) {
    const series = data.series;
    const ventasLabels = series.ventas_diarias.map(i => i.fecha);
    const ventasValues = series.ventas_diarias.map(i => Number(i.total));

    const metodoLabels = series.metodo_pago.map(i => i.metodo_pago);
    const metodoValues = series.metodo_pago.map(i => Number(i.cantidad));

    const topLabels = series.top_productos.map(i => i.producto);
    const topValues = series.top_productos.map(i => Number(i.revenue));

    if (chartVentasDiarias) chartVentasDiarias.destroy();
    if (chartMetodoPago) chartMetodoPago.destroy();
    if (chartTopProductos) chartTopProductos.destroy();

    chartVentasDiarias = new Chart(document.getElementById('chartVentasDiarias'), {
        type: 'line',
        data: {
            labels: ventasLabels,
            datasets: [{ label: 'Ventas Bs.', data: ventasValues, borderColor: '#2E75B6', backgroundColor: 'rgba(46,117,182,0.2)', fill: true }]
        },
        options: { responsive: true }
    });

    chartMetodoPago = new Chart(document.getElementById('chartMetodoPago'), {
        type: 'pie',
        data: {
            labels: metodoLabels,
            datasets: [{ data: metodoValues, backgroundColor: ['#2E75B6', '#1F6B40', '#C55A11', '#8E44AD'] }]
        },
        options: { responsive: true }
    });

    chartTopProductos = new Chart(document.getElementById('chartTopProductos'), {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{ label: 'Revenue Bs.', data: topValues, backgroundColor: '#1F6B40' }]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } } }
    });
}

function updateCards(cards) {
    document.getElementById('kpi_ventas_periodo').textContent = cards.ventas_periodo;
    document.getElementById('kpi_total_vendido').textContent = `Bs. ${Number(cards.total_vendido).toFixed(2)}`;
    document.getElementById('kpi_stock_bajo').textContent = cards.stock_bajo;
    document.getElementById('kpi_pedidos_pendientes').textContent = cards.pedidos_pendientes;
    document.getElementById('kpi_pedidos_periodo').textContent = cards.pedidos_periodo;
    document.getElementById('kpi_tickets').textContent = cards.tickets_emitidos;
}

async function fetchKpis() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    const url = `{{ route('dashboard.kpis') }}?fecha_inicio=${encodeURIComponent(inicio)}&fecha_fin=${encodeURIComponent(fin)}`;
    const response = await fetch(url);
    const payload = await response.json();
    updateCards(payload.data.cards);
    buildCharts(payload.data);
}

document.getElementById('btnFiltrar').addEventListener('click', fetchKpis);
buildCharts(kpisIniciales);
</script>
@endsection