# Informe de Cierre de Sprints (1, 2 y 3)
**Carrera:** Ingenieria de Sistemas  
**Materia:** Sistemas de Informacion  
**Proyecto:** Sistema de Informacion para el Restaurante Patio del Majau  
**Equipo:** Grupo Majau  
**Fecha:** 2026-07-02

---

## 1) Objetivo del proyecto
Disenar e implementar un sistema web integral para el restaurante Patio del Majau que permita digitalizar el flujo operativo y comercial (usuarios, productos, inventario, pedidos, mesas, ventas y facturacion), entregando informacion confiable para la toma de decisiones y mejorando tiempos de atencion, control de stock y trazabilidad del negocio.

## 2) Sprint Goals por sprint

### Sprint 1 - Goal
Construir el flujo minimo operativo del negocio con funcionalidades Must Have: autenticacion, gestion base de usuarios/productos, registro de pedidos y ventas, facturacion y alertas de stock bajo.

**Cumplimiento Sprint 1:** Alcanzado.  
**Evidencia tecnica:** `Sprint1FeatureTest` en PASS.

### Sprint 2 - Goal
Completar modulos de negocio Should Have con arquitectura en capas (controllers/services/repositories), CRUDs con borrado logico y validaciones, documentacion API y soporte a reportes.

**Cumplimiento Sprint 2:** Alcanzado.  
**Evidencia tecnica:** `Sprint2FeatureTest` en PASS.

### Sprint 3 - Goal
Incorporar analitica de ventas y soporte gerencial mediante dashboard con KPIs, graficas y filtros dinamicos; ademas de historial de ventas filtrable y pruebas de integracion.

**Cumplimiento Sprint 3:** Alcanzado.  
**Evidencia tecnica:** `Sprint3FeatureTest` en PASS.

---

## 3) Verificacion global del proyecto (estado actual)
- Suite automatizada ejecutada: `php artisan test`
- Resultado actual: **15 passed (72 assertions)**
- Cobertura funcional validada en:
  - `Tests\Feature\Sprint1FeatureTest`
  - `Tests\Feature\Sprint2FeatureTest`
  - `Tests\Feature\Sprint3FeatureTest`

**Conclusion de verificacion:** El estado funcional de los tres sprints es estable y consistente con los objetivos definidos.

---

## 4) Sprint Review - Demo completa del sistema

### Flujo de demo recomendado (12-15 minutos)
1. Iniciar sesion con usuario administrador (HU-01).
2. Modulo Usuarios: crear/editar/desactivar usuario (HU-08, HU-09, HU-10).
3. Modulo Productos: crear/editar/desactivar producto + filtros (HU-14, HU-15, HU-16).
4. Menu: visualizacion por categorias y carrito de apoyo para pedido (HU-13).
5. Inventario: revisar alertas y abastecer stock (HU-19, HU-04).
6. Compras de insumos: registrar compra y comprobar incremento de stock (HU-05).
7. Mesas: registrar mesa y cambio de estado (HU-11, HU-12).
8. Pedidos: registrar pedido y flujo de estado (HU-02, HU-20).
9. Ventas: registrar venta con cajero autocompletado y generar factura (HU-03, HU-17).
10. Dashboard: revisar KPIs, graficas y filtros por fecha (HU-06 / Actividad 7).
11. Historial de ventas por fechas (HU-18).

### Resultado de review
- Flujo end-to-end: **Operativo**
- Integracion entre modulos: **Consistente**
- Evidencias de prueba automatica: **PASS**

---

## 5) Retrospectiva por sprint (Start / Stop / Continue + lecciones)

## Sprint 1 Retrospectiva
### Start
- Definir checklists DoD por HU (codigo + prueba + evidencia + commit).
- Probar regresiones al cerrar cada HU critica.

### Stop
- Acumular demasiados cambios sin verificacion intermedia.
- Ajustar textos funcionales sin revisar impacto en pruebas.

### Continue
- Priorizacion por valor de negocio (MoSCoW).
- Entrega incremental del flujo comercial (pedido -> venta -> factura).

### Lecciones aprendidas
- El valor temprano se logra implementando primero el nucleo del negocio.
- Los cambios de UI/texto deben tener trazabilidad para no romper pruebas.

## Sprint 2 Retrospectiva
### Start
- Fortalecer contratos entre capas (controller/service/repository).
- Estandarizar respuestas y validaciones en formularios.

### Stop
- Dejar documentacion API para el final del sprint.
- Mezclar cambios de logica y cambios visuales en el mismo bloque sin control.

### Continue
- Borrado logico y paginacion en CRUDs principales.
- Pruebas de modulos de negocio al cierre de cada bloque.

### Lecciones aprendidas
- La arquitectura en capas reduce retrabajo y facilita mantenimiento.
- Integrar compras + inventario da trazabilidad operativa real.

## Sprint 3 Retrospectiva
### Start
- Medir rendimiento de consultas de KPIs en ambientes con mas datos.
- Extender pruebas de integracion para escenarios de borde.

### Stop
- Depender solo de verificacion visual de dashboards.
- Posponer la matriz de pruebas hasta fin de sprint.

### Continue
- Uso de datos agregados para soporte gerencial.
- Filtros dinamicos y visualizacion de tendencias para decisiones.

### Lecciones aprendidas
- La analitica aporta valor cuando se conecta al flujo operativo real.
- Las pruebas con fechas y datos historicos evitan regresiones silenciosas.

---

## 6) Matriz de pruebas (10+ casos) con PASS/FAIL y bugs

| ID | Sprint/HU | Caso | Resultado esperado | Resultado obtenido | Estado |
|---|---|---|---|---|---|
| CP-01 | S1/HU-01 | Login valido | Redirige al dashboard segun rol | Autenticacion correcta con usuario activo | PASS |
| CP-02 | S1/HU-08 | Registrar usuario nuevo | Guarda usuario con hash y datos validos | Registro correcto y persistencia en BD | PASS |
| CP-03 | S1/HU-14 | Registrar producto con insumos | Asocia receta producto-insumo | Asociacion guardada correctamente | PASS |
| CP-04 | S1/HU-19 | Alerta stock bajo en inventario | Visualiza alerta y permite actualizar stock | Flujo correcto; ajuste de stock aplicado | PASS |
| CP-05 | S1/HU-02 | Registrar pedido con validacion stock | Crea pedido y descuenta insumos | Pedido creado y stock actualizado | PASS |
| CP-06 | S1/HU-03/HU-17 | Registrar venta + factura | Monto automatico y factura emitida | Venta/factura creadas correctamente | PASS |
| CP-07 | S2/HU-04 | Ajuste manual inventario | Registra movimiento de inventario | Movimiento almacenado y visible | PASS |
| CP-08 | S2/HU-05 | Registrar compra de insumos | Incrementa stock y crea detalle compra | Compra registrada con impacto en inventario | PASS |
| CP-09 | S2/HU-11/HU-12 | CRUD mesas + cambio estado | Operaciones de mesas funcionales | Flujo completo de mesas operativo | PASS |
| CP-10 | S2/HU-10/HU-16 | Borrado logico usuarios/productos | No elimina fisicamente; desactiva registro | Comportamiento correcto en BD | PASS |
| CP-11 | S3/HU-06 | Endpoint KPIs filtrable | JSON con cards/series segun fechas | Respuesta correcta y consumida por dashboard | PASS |
| CP-12 | S3/HU-18 | Historial ventas por fecha | Lista solo ventas del rango | Filtrado correcto por fechas | PASS |
| CP-13 | S1/HU-19 (historico) | Cambio de texto UI inventario | Test espera cadena `Stock bajo` | Mostraba `Alerta` y fallo temporal de test | FAIL (historico) |

### Bugs encontrados y estado
| Bug | Contexto | Accion | Estado |
|---|---|---|---|
| Texto inconsistente `Stock bajo` vs `Alerta` | Inventario HU-19 | Se restauro texto esperado por pruebas | Resuelto |
| Falla historica HU-18 por timestamp en ventas | Prueba de historial | Ajuste explicito de `created_at` en prueba | Resuelto |
| Columna `activo` no encontrada (historico) | Ambiente sin migraciones al dia | Ejecutar migraciones pendientes y compatibilidad | Resuelto |

---

## 7) Endpoints documentados (con body/response ejemplo)

> Nota: El proyecto usa principalmente rutas web (Blade). Por ello, la respuesta tipica es HTML + redireccion + mensaje flash.  
> Endpoint JSON principal: `GET /dashboard/kpis`.

## 7.1 Autenticacion y dashboard

### POST `/login`
**Body (form-data):**
```json
{
  "usuario": "admin@demo.com",
  "password": "Demo123456"
}
```
**Response ejemplo:**
```json
{
  "type": "redirect",
  "to": "/dashboard",
  "flash": "Autenticado"
}
```

### GET `/dashboard/kpis`
**Query params ejemplo:** `?fecha_inicio=2026-06-01&fecha_fin=2026-06-30`  
**Response ejemplo (JSON):**
```json
{
  "success": true,
  "fecha_inicio": "2026-06-01",
  "fecha_fin": "2026-06-30",
  "data": {
    "cards": {
      "ventas_periodo": 12,
      "total_vendido": 1560.5,
      "pedidos_periodo": 18,
      "pedidos_pendientes": 2,
      "stock_bajo": 1,
      "tickets_emitidos": 12
    },
    "series": {
      "ventas_diarias": [],
      "metodo_pago": [],
      "top_productos": []
    }
  }
}
```

## 7.2 Empleados

### POST `/empleados`
```json
{
  "nombre": "Maria",
  "apellido": "Flores",
  "usuario": "maria.f",
  "password": "Secret123",
  "password_confirmation": "Secret123",
  "rol": "Cajero",
  "estado": "Activo"
}
```
**Response:** redirect a `/empleados` + flash `Usuario registrado correctamente.`

### PUT `/empleados/{id}`
```json
{
  "nombre": "Maria",
  "apellido": "Flores",
  "usuario": "maria.f",
  "rol": "Cajero",
  "estado": "Activo"
}
```
**Response:** redirect a `/empleados` + flash de actualizacion.

### DELETE `/empleados/{id}`
**Body:** vacio  
**Response:** redirect a `/empleados` + desactivacion logica.

## 7.3 Productos y menu

### POST `/productos`
```json
{
  "nombre": "Majau Batido",
  "descripcion": "Bebida de la casa",
  "precio": 18.5,
  "categoria": "Bebidas",
  "estado": "Activo",
  "insumos": [1, 2],
  "cantidades": {
    "1": 0.5,
    "2": 0.2
  }
}
```
**Response:** redirect a `/productos` + producto creado.

### PUT `/productos/{id}`
```json
{
  "nombre": "Majau Batido XL",
  "descripcion": "Version grande",
  "precio": 22,
  "categoria": "Bebidas",
  "estado": "Activo"
}
```
**Response:** redirect a `/productos` + producto actualizado.

### DELETE `/productos/{id}`
**Body:** vacio  
**Response:** redirect a `/productos` + borrado logico.

### GET `/menu`
**Response:** HTML con productos activos agrupados por categoria.

## 7.4 Inventario y compras de insumos

### PUT `/inventario/{insumo}`
```json
{
  "stock_actual": 12,
  "stock_minimo": 5,
  "unidad_medida": "kg"
}
```
**Response:** redirect a `/inventario` + stock actualizado.

### POST `/compras-insumos`
```json
{
  "proveedor_id": 1,
  "observacion": "Compra semanal",
  "items": [
    { "insumo_id": 1, "cantidad": 10, "precio_unitario": 8.5 },
    { "insumo_id": 2, "cantidad": 5, "precio_unitario": 12.0 }
  ]
}
```
**Response:** redirect a `/compras-insumos/{id}` + compra registrada.

### GET `/compras-insumos/{id}`
**Response:** HTML con cabecera de compra y detalle por insumo.

## 7.5 Mesas

### POST `/mesas`
```json
{
  "numero_mesa": 8,
  "capacidad": 4,
  "estado": "Disponible"
}
```
**Response:** redirect a `/mesas` + mesa registrada.

### PUT `/mesas/{id}`
```json
{
  "numero_mesa": 8,
  "capacidad": 6,
  "estado": "Reservada"
}
```
**Response:** redirect a `/mesas` + mesa actualizada.

### POST `/mesas/{mesa}/cambiar-estado`
```json
{
  "estado": "Ocupada"
}
```
**Response:** redirect a `/mesas` + estado actualizado.

### DELETE `/mesas/{id}`
**Body:** vacio  
**Response:** redirect a `/mesas` + desactivacion logica.

## 7.6 Pedidos

### POST `/pedidos`
```json
{
  "mesa_id": 3,
  "empleado_id": 2,
  "tipo_pedido": "Mesa",
  "items": [
    { "producto_id": 5, "cantidad": 2 },
    { "producto_id": 8, "cantidad": 1 }
  ]
}
```
**Response:** redirect a `/pedidos` + pedido registrado.

### POST `/pedidos/{pedido}/cambiar-estado`
```json
{
  "estado": "En Preparacion"
}
```
**Nota:** valor esperado por backend: `En Preparación` (con tilde).  
**Response:** redirect a `/pedidos` + estado actualizado.

### GET `/pedidos/{id}`
**Response:** HTML con detalle de pedido y productos.

## 7.7 Ventas y facturas

### POST `/ventas`
```json
{
  "pedido_id": 1024,
  "metodo_pago": "Efectivo",
  "razon_social_cliente": "Consumidor Final",
  "nit_cliente": "",
  "generar_factura": true
}
```
**Response:** redirect a `/ventas/{id}` + venta registrada.

### GET `/ventas/{id}`
**Response:** HTML con detalle de venta.

### GET `/ventas/{venta}/factura`
**Response:** HTML factura imprimible (si no existe, se genera automaticamente).

---

## 8) Conclusiones finales
1. El proyecto cumple funcionalmente los objetivos definidos en Sprint 1, Sprint 2 y Sprint 3.
2. Se consolido un flujo operativo completo: inventario/compras -> pedidos/mesas -> ventas/factura -> dashboard gerencial.
3. El estado actual del sistema es verificable por pruebas automatizadas (suite completa en PASS) y por flujo de demo integral.
4. La principal mejora futura recomendada es fortalecer pruebas E2E y observabilidad de rendimiento en consultas de KPIs.

