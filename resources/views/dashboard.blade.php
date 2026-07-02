@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-shell p-3 p-md-4">
        <div class="dashboard-header">
            <div>
                <h2 class="dashboard-title">Buenas tardes, {{ auth()->user()->nombre ?? 'Equipo' }} 👋</h2>
                <p class="dashboard-subtitle">Aquí está el resumen operacional del restaurante para la toma de decisiones.</p>
            </div>
            <div class="dashboard-chip">
                <span>📅</span>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</span>
            </div>
        </div>

        <div class="dashboard-filter mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" class="form-control rounded-pill" id="fecha_inicio" value="{{ $fechaInicio }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" class="form-control rounded-pill" id="fecha_fin" value="{{ $fechaFin }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rango rápido</label>
                    <div class="quick-range">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-range active" data-range="7d">7 días</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-range" data-range="30d">30 días</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-range" data-range="mes">Mes actual</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" id="btnFiltrar" class="btn btn-dark w-100 rounded-pill">
                        <span id="btnFiltrarLabel">Aplicar filtros</span>
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" id="btnLimpiar" class="btn btn-outline-secondary w-100 rounded-pill">Limpiar</button>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-primary">
                    <div class="card-body">
                        <div class="kpi-icon">🧾</div>
                        <p class="kpi-label">Ventas del período</p>
                        <h3 class="kpi-value" id="kpi_ventas_periodo">{{ $kpis['cards']['ventas_periodo'] }}</h3>
                        <p class="kpi-help">Tickets emitidos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-success">
                    <div class="card-body">
                        <div class="kpi-icon">💳</div>
                        <p class="kpi-label">Total vendido</p>
                        <h3 class="kpi-value" id="kpi_total_vendido">Bs. {{ number_format($kpis['cards']['total_vendido'], 2) }}</h3>
                        <p class="kpi-help">Ingresos acumulados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-info">
                    <div class="card-body">
                        <div class="kpi-icon">📈</div>
                        <p class="kpi-label">Ticket promedio</p>
                        <h3 class="kpi-value" id="kpi_ticket_promedio">Bs. 0.00</h3>
                        <p class="kpi-help">Valor por venta</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-danger">
                    <div class="card-body">
                        <div class="kpi-icon">⏳</div>
                        <p class="kpi-label">Pedidos pendientes</p>
                        <h3 class="kpi-value" id="kpi_pedidos_pendientes">{{ $kpis['cards']['pedidos_pendientes'] }}</h3>
                        <p class="kpi-help">En cola operativa</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-warning">
                    <div class="card-body">
                        <div class="kpi-icon">⚠️</div>
                        <p class="kpi-label">Stock bajo</p>
                        <h3 class="kpi-value" id="kpi_stock_bajo">{{ $kpis['cards']['stock_bajo'] }}</h3>
                        <p class="kpi-help">Insumos en alerta</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card kpi-card kpi-accent-dark">
                    <div class="card-body">
                        <div class="kpi-icon">🧺</div>
                        <p class="kpi-label">Pedidos del período</p>
                        <h3 class="kpi-value" id="kpi_pedidos_periodo">{{ $kpis['cards']['pedidos_periodo'] }}</h3>
                        <p class="kpi-help">Tickets: <span id="kpi_tickets">{{ $kpis['cards']['tickets_emitidos'] }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-8">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Tendencia de ventas</span>
                        <span class="badge text-bg-light" id="badgeRango">Últimos 7 días</span>
                    </div>
                    <div class="card-body">
                        <canvas id="chartVentasDiarias" height="110"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Métodos de pago</span>
                        <span class="badge text-bg-light">Transacciones</span>
                    </div>
                    <div class="card-body">
                        <canvas id="chartMetodoPago" height="110"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Distribución por producto</span>
                        <span class="badge text-bg-light">% del total</span>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDistribucion" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Top productos más vendidos</span>
                        <div class="chart-toolbar">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill js-top-mode active" data-mode="revenue">Revenue</button>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill js-top-mode" data-mode="cantidad">Cantidad</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTopProductos" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @if($alertasStock->isNotEmpty())
            <div class="alert alert-stock-custom mt-3">
                <strong>HU-19: alerta de stock bajo</strong>
                <ul class="mb-0 mt-2">
                    @foreach($alertasStock as $insumo)
                        <li>{{ $insumo->nombre }}: {{ $insumo->stock_actual }} {{ $insumo->unidad_medida }} (mínimo: {{ $insumo->stock_minimo }})</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const kpisIniciales = @json($kpis);
let chartVentasDiarias;
let chartMetodoPago;
let chartDistribucion;
let chartTopProductos;
let topMode = 'revenue';
let latestData = JSON.parse(JSON.stringify(kpisIniciales));
const colorPalette = ['#ff9800', '#ec4899', '#6d4c41', '#9e8e7f', '#1f1611', '#b0a296', '#7f5539'];

function formatMoney(value) {
    return `Bs. ${Number(value || 0).toFixed(2)}`;
}

function setLoading(isLoading) {
    const btn = document.getElementById('btnFiltrar');
    const label = document.getElementById('btnFiltrarLabel');
    btn.disabled = isLoading;
    label.textContent = isLoading ? 'Cargando...' : 'Aplicar filtros';
}

function applyQuickRange(range) {
    const start = document.getElementById('fecha_inicio');
    const end = document.getElementById('fecha_fin');
    const today = new Date();
    let from = new Date();

    if (range === '7d') from.setDate(today.getDate() - 6);
    if (range === '30d') from.setDate(today.getDate() - 29);
    if (range === 'mes') from = new Date(today.getFullYear(), today.getMonth(), 1);

    start.value = from.toISOString().split('T')[0];
    end.value = today.toISOString().split('T')[0];
}

function updateRangoBadge() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    document.getElementById('badgeRango').textContent = `${inicio} → ${fin}`;
}

function animateValue(elementId, finalValue, options = {}) {
    const element = document.getElementById(elementId);
    const prefix = options.prefix || '';
    const decimals = options.decimals || 0;
    const duration = 420;
    const startTime = performance.now();

    function tick(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        const current = finalValue * progress;
        element.textContent = `${prefix}${current.toFixed(decimals)}`;
        if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

function destroyChart(chartRef) {
    if (chartRef) chartRef.destroy();
}

function buildCharts(data) {
    const series = data.series;
    const ventasLabels = series.ventas_diarias.map(i => i.fecha);
    const ventasValues = series.ventas_diarias.map(i => Number(i.total));
    const metodoLabels = series.metodo_pago.map(i => i.metodo_pago);
    const metodoValues = series.metodo_pago.map(i => Number(i.cantidad));
    const topLabels = series.top_productos.map(i => i.producto);
    const topValues = series.top_productos.map(i => Number(i[topMode]));
    const distLabels = series.top_productos.slice(0, 6).map(i => i.producto);
    const distValues = series.top_productos.slice(0, 6).map(i => Number(i.revenue));

    destroyChart(chartVentasDiarias);
    destroyChart(chartMetodoPago);
    destroyChart(chartDistribucion);
    destroyChart(chartTopProductos);

    chartVentasDiarias = new Chart(document.getElementById('chartVentasDiarias'), {
        type: 'bar',
        data: {
            labels: ventasLabels,
            datasets: [{
                label: 'Ventas Bs.',
                data: ventasValues,
                borderRadius: 10,
                maxBarThickness: 36,
                backgroundColor: ['#b0a296', '#9e8e7f', '#6d4c41', '#ff9800', '#ec4899', '#ff9800', '#8f7f73']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: context => ` ${formatMoney(context.parsed.y)}` } }
            },
            scales: {
                y: { ticks: { callback: value => `Bs. ${value}` } }
            }
        }
    });

    chartMetodoPago = new Chart(document.getElementById('chartMetodoPago'), {
        type: 'bar',
        data: { labels: metodoLabels, datasets: [{ data: metodoValues, backgroundColor: colorPalette }] },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    chartDistribucion = new Chart(document.getElementById('chartDistribucion'), {
        type: 'pie',
        data: { labels: distLabels, datasets: [{ data: distValues, backgroundColor: colorPalette }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    chartTopProductos = new Chart(document.getElementById('chartTopProductos'), {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{
                label: topMode === 'revenue' ? 'Ingresos Bs.' : 'Cantidad vendida',
                data: topValues,
                backgroundColor: colorPalette
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
}

function updateCards(cards) {
    const ticketPromedio = cards.ventas_periodo > 0
        ? Number(cards.total_vendido) / Number(cards.ventas_periodo)
        : 0;

    animateValue('kpi_ventas_periodo', Number(cards.ventas_periodo), { decimals: 0 });
    animateValue('kpi_total_vendido', Number(cards.total_vendido), { decimals: 2, prefix: 'Bs. ' });
    animateValue('kpi_ticket_promedio', Number(ticketPromedio), { decimals: 2, prefix: 'Bs. ' });
    animateValue('kpi_pedidos_pendientes', Number(cards.pedidos_pendientes), { decimals: 0 });
    animateValue('kpi_stock_bajo', Number(cards.stock_bajo), { decimals: 0 });
    animateValue('kpi_pedidos_periodo', Number(cards.pedidos_periodo), { decimals: 0 });
    animateValue('kpi_tickets', Number(cards.tickets_emitidos), { decimals: 0 });
}

async function fetchKpis() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    const url = `{{ route('dashboard.kpis') }}?fecha_inicio=${encodeURIComponent(inicio)}&fecha_fin=${encodeURIComponent(fin)}`;
    try {
        setLoading(true);
        const response = await fetch(url);
        const payload = await response.json();
        latestData = payload.data;
        updateCards(payload.data.cards);
        buildCharts(payload.data);
        updateRangoBadge();
    } catch (error) {
        alert('No se pudo cargar el dashboard. Intente nuevamente.');
    } finally {
        setLoading(false);
    }
}

document.querySelectorAll('.js-range').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.js-range').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyQuickRange(btn.dataset.range);
    });
});

document.getElementById('btnLimpiar').addEventListener('click', () => {
    applyQuickRange('7d');
    fetchKpis();
});

document.querySelectorAll('.js-top-mode').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.js-top-mode').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        topMode = btn.dataset.mode;
        buildCharts(latestData);
    });
});

document.getElementById('btnFiltrar').addEventListener('click', fetchKpis);
updateCards(kpisIniciales.cards);
buildCharts(kpisIniciales);
updateRangoBadge();
</script>
@endsection