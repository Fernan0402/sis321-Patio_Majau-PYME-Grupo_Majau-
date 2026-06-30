# Sprint 3 - Review y Retrospectiva

## Sprint Review (guion de demo final - 10 minutos)
1. **Min 1-2:** Login con usuario demo administrador (`admin@demo.com`).
2. **Min 2-4:** Flujo operativo rapido: productos -> pedido -> venta -> factura.
3. **Min 4-6:** HU-18, historial de ventas por rango de fechas.
4. **Min 6-9:** HU-06 + Actividad 7, dashboard KPI y graficos (linea, pie, barras) con filtro dinamico.
5. **Min 9-10:** Evidencia API `GET /dashboard/kpis` y cierre con valor para gerencia.

## Checklist pre-demo
- Ejecutar `php artisan test` y validar suite en verde.
- Confirmar datos semilla y usuario demo disponibles.
- Verificar conexion a BD y carga de assets (Vite/Bootstrap).
- Ensayar flujo completo 3 veces.

## Evidencia de 3 corridas de demo
- Corrida 1: Login -> pedido -> venta -> factura -> dashboard. Resultado: PASS.
- Corrida 2: Login admin -> filtro historial ventas -> filtro KPIs -> ver graficos. Resultado: PASS.
- Corrida 3: Login por rol permitido -> navegacion modulos -> endpoint KPI -> cierre. Resultado: PASS.

## Plan B para demo
- Ejecutar demo en localhost con base de datos precargada.
- Mantener script de seeders listo (`php artisan migrate:fresh --seed`).
- Si falla la conexion BD, mostrar captura previa y ejecutar con backup local de la BD.

## Retrospectiva (Start / Stop / Continue)
### Start
- Automatizar mas pruebas de integracion end-to-end para dashboard.
- Registrar metricas de rendimiento de consultas para KPIs.

### Stop
- Depender de pruebas manuales tardias antes de demo.
- Mezclar cambios funcionales y documentacion en una sola revision grande.

### Continue
- Desarrollo por HU priorizadas.
- Arquitectura en capas para nuevos modulos.
- Validaciones y borrado logico en entidades de negocio.
