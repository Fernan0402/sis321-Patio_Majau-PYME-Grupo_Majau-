-- SPRINT 2 - Consultas SQL de Reportes (Actividad 6)
-- BD: MySQL

-- 1) Top productos por ingresos en rango de fechas
SELECT
    p.id AS producto_id,
    p.nombre AS producto,
    SUM(dp.cantidad) AS total_vendido,
    SUM(dp.subtotal) AS revenue
FROM detalle_pedidos dp
INNER JOIN productos p ON p.id = dp.producto_id
INNER JOIN pedidos pe ON pe.id = dp.pedido_id
WHERE pe.created_at BETWEEN :fecha_inicio AND :fecha_fin
GROUP BY p.id, p.nombre
ORDER BY revenue DESC
LIMIT 10;

-- 2) Insumos con mayor consumo estimado por ventas (JOIN + GROUP BY + HAVING)
SELECT
    i.id AS insumo_id,
    i.nombre AS insumo,
    i.stock_actual,
    i.stock_minimo,
    SUM(dp.cantidad * pi.cantidad_necesaria) AS consumo_estimado
FROM detalle_pedidos dp
INNER JOIN producto_insumo pi ON pi.producto_id = dp.producto_id
INNER JOIN insumos i ON i.id = pi.insumo_id
GROUP BY i.id, i.nombre, i.stock_actual, i.stock_minimo
HAVING consumo_estimado > 0
ORDER BY consumo_estimado DESC;

-- 3) Ventas por método de pago y día
SELECT
    DATE(v.created_at) AS fecha,
    v.metodo_pago,
    COUNT(*) AS total_transacciones,
    SUM(v.monto_total) AS total_facturado
FROM ventas v
GROUP BY DATE(v.created_at), v.metodo_pago
ORDER BY fecha DESC, total_facturado DESC;

-- 4) Rotación de mesas (pedidos por mesa)
SELECT
    m.id AS mesa_id,
    m.numero_mesa,
    COUNT(pe.id) AS pedidos_atendidos,
    SUM(pe.total) AS total_generado
FROM mesas m
LEFT JOIN pedidos pe ON pe.mesa_id = m.id
GROUP BY m.id, m.numero_mesa
ORDER BY pedidos_atendidos DESC, total_generado DESC;

-- 5) Tiempo promedio por estado de pedido (subconsulta + agregaciones)
SELECT
    base.estado,
    ROUND(AVG(base.minutos_transcurridos), 2) AS promedio_minutos
FROM (
    SELECT
        p.id,
        p.estado,
        TIMESTAMPDIFF(MINUTE, p.created_at, COALESCE(p.updated_at, p.created_at)) AS minutos_transcurridos
    FROM pedidos p
) AS base
GROUP BY base.estado
ORDER BY promedio_minutos DESC;

-- 6) Proveedores con mayor monto de compra en un periodo
SELECT
    pr.id AS proveedor_id,
    pr.nombre AS proveedor,
    COUNT(ci.id) AS compras_registradas,
    SUM(ci.monto_total) AS monto_total_comprado
FROM proveedores pr
INNER JOIN compra_insumos ci ON ci.proveedor_id = pr.id
WHERE ci.created_at BETWEEN :fecha_inicio AND :fecha_fin
GROUP BY pr.id, pr.nombre
ORDER BY monto_total_comprado DESC;
