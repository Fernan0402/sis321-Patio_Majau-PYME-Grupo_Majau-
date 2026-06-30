# Sprint 3 - Pruebas de Integracion

## Matriz de casos (minimo 10)

| Caso de prueba | Precondicion | Datos de entrada | Resultado esperado | Resultado obtenido | Estado |
|---|---|---|---|---|---|
| Login con usuario administrador activo | Usuario existente | usuario=admin@demo.com, contrasena=Demo123456 | Acceso correcto al dashboard | Cubierto por `Sprint1FeatureTest::test_hu01_login_exitoso_y_fallido` | PASS |
| Acceso a dashboard protegido sin login | Sesion cerrada | GET /dashboard | Redireccion a login | Cubierto por `ExampleTest` (redirect esperado) | PASS |
| KPI JSON responde correctamente | Sesion admin iniciada | GET /dashboard/kpis | JSON con `success=true`, `cards` y `series` | Implementado y probado por test automatizado | PASS |
| Filtro dashboard por fechas | Sesion admin iniciada | fecha_inicio/fecha_fin validas | Actualiza KPIs y graficos | Validado por endpoint filtrable `Sprint3FeatureTest::test_hu06_endpoint_kpis_retorna_datos_filtrables` | PASS |
| HU-06 ventas diarias en grafico | Existen ventas en rango | Dashboard con rango de fechas | Grafico de linea muestra totales diarios | Series `ventas_diarias` disponibles y consumidas por Chart.js | PASS |
| HU-18 historial de ventas por fecha | Existen ventas historicas | GET /ventas?fecha_inicio=...&fecha_fin=... | Lista solo ventas del rango | Implementado y probado por test automatizado | PASS |
| CRUD mesas sigue operativo tras integracion | Sesion admin iniciada | Crear/editar/cambiar estado mesa | Operaciones exitosas sin errores | Cubierto por `Sprint2FeatureTest::test_hu11_hu12_registro_y_cambio_estado_mesa` | PASS |
| Registro de pedido + venta + factura integrado | Datos base cargados | Crear pedido y registrar venta | Se genera venta y factura asociada | Cubierto por `Sprint1FeatureTest::test_hu03_hu17_venta_calcula_monto_y_genera_factura_con_detalle` | PASS |
| Alertas stock bajo visibles en dashboard | Insumos con stock <= minimo | Abrir dashboard | Se listan alertas de stock | Cubierto por `Sprint1FeatureTest::test_hu19_inventario_muestra_alerta_y_actualiza_stock` | PASS |
| Navegacion por roles sin rutas indebidas | Usuarios por rol existentes | Login por rol + menu | Accesos permitidos/restringidos correctamente | Cubierto por `Sprint1FeatureTest::test_rutas_restringidas_por_rol` | PASS |
| Flujo demo completo (3 repeticiones) | Datos demo cargados | Login -> pedidos -> ventas -> dashboard | Flujo estable y repetible | Ejecutado en test suite completa sin regresiones (15/15 PASS) | PASS |

## Evidencia de ejecucion
- Comando ejecutado: `php artisan test`
- Resultado: `15 passed (72 assertions)`
- Evidencia manual de demo (3 corridas): registrada en `docs/SPRINT3_REVIEW_RETROSPECTIVA.md`.

## Bugs encontrados y estado
- Bug detectado en prueba HU-18 por timestamps no aplicados en `Venta::create`.
- Correccion: actualizar `created_at` de la venta historica con `update` posterior a la creacion.
- Estado actual: resuelto, sin fallos en suite completa.
