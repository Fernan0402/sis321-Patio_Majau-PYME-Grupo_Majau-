# Planificación Sprint 2
**Proyecto:** Sistema de Información - Restaurante Patio del Majau  
**Sprint:** 2 (Semana 1-2)  
**Objetivo:** Completar módulos de negocio (Should Have), reforzar arquitectura en capas y cumplir Definition of Done.

## 1) Historias de Usuario Sprint 2

| HU | Historia | Prioridad | SP |
|---|---|---|---:|
| HU-04 | Gestionar inventario | Should Have | 8 |
| HU-05 | Registrar compra de insumos | Should Have | 5 |
| HU-09 | Editar usuarios | Should Have | 3 |
| HU-10 | Eliminar usuarios | Should Have | 2 |
| HU-11 | Registrar mesas | Should Have | 2 |
| HU-12 | Cambiar estado de mesa | Should Have | 3 |
| HU-15 | Editar productos | Should Have | 3 |
| HU-16 | Eliminar productos | Should Have | 2 |
| HU-20 | Actualizar estado del pedido | Should Have | 5 |
|  | **Total** |  | **33** |

## 2) Diagnóstico inicial del estado del código

Con base en el repositorio actual:

- **Parcialmente ya implementadas:** HU-09, HU-10, HU-15, HU-16, HU-20.
- **Pendientes fuertes:** HU-04 (inventario completo con movimientos), HU-05 (compras + proveedor), HU-11/HU-12 (módulo de mesas dedicado).
- **Pendiente transversal:** arquitectura en capas explícita (controllers/services/repositories) para módulos Sprint 2.
- **Pendiente de rúbrica:** 5+ consultas SQL de reportes, colección Postman completa, burndown diario y paquete APA7 Sprint 2.

## 3) Alcance técnico de Sprint 2

## 3.1 Arquitectura en capas (Actividad 6 - Paso 1)

Estructura objetivo:

- `app/Http/Controllers/` -> capa HTTP
- `app/Services/` -> reglas de negocio
- `app/Repositories/` -> acceso a datos

Módulos que deben migrarse a esta estructura en Sprint 2:

- Inventario
- Compra de insumos
- Mesas

## 3.2 CRUD estándar (Actividad 6 - Paso 2)

Para al menos 3 módulos del negocio:

- `GET /entidad` (listar con paginación)
- `GET /entidad/{id}` (obtener uno)
- `POST /entidad` (crear con validación)
- `PUT /entidad/{id}` (actualizar)
- `DELETE /entidad/{id}` (borrado lógico: `activo=false`)

## 3.3 Consultas SQL para reportes (Actividad 6 - Paso 3)

Meta mínima: **5 consultas complejas** con `JOIN`, `GROUP BY`, `HAVING` y al menos una subconsulta.

Consultas objetivo:

1. Top productos por ingresos en rango de fechas.
2. Insumos críticos por consumo vs stock.
3. Ventas por método de pago y día.
4. Mesas con mayor rotación (pedidos atendidos).
5. Tiempo medio de ciclo del pedido por estado.

## 3.4 Postman (Actividad 6 - Paso 4)

Colección: `API Patio Majau - Sprint2`.

Debe incluir por endpoint:

- nombre descriptivo
- método y URL
- body ejemplo
- response ejemplo

## 3.5 Validaciones (Actividad 6 - Paso 5)

En Laravel:

- `FormRequest` o validaciones de controlador
- errores JSON estandarizados cuando sea endpoint API:
  - `{ "success": false, "errors": [...] }`

## 3.6 Burndown (Actividad 6 - Paso 6)

- Total inicial: 33 SP
- Registro diario: SP restantes reales vs línea ideal
- Evidencia: gráfico en Excel/Sheets + archivo exportado

## 4) Plan de ejecución por bloques

## Bloque A (Día 1-2) - Base de arquitectura y datos

- Crear capas `Services` y `Repositories`.
- Definir migraciones para:
  - proveedores
  - compras de insumos
  - movimientos de inventario
  - campo `activo` en entidades objetivo (borrado lógico)

## Bloque B (Día 3-5) - Módulos funcionales

- HU-04 Inventario completo (consulta, ajuste, movimientos).
- HU-05 Compra de insumos (crear compra, proveedor, impacto en stock).
- HU-11/HU-12 Mesas (CRUD + cambio de estado).

## Bloque C (Día 6-8) - Refactor y cierre Should Have

- HU-09/10 Usuarios con borrado lógico y paginación.
- HU-15/16 Productos con borrado lógico y paginación.
- HU-20 Estado pedido con flujo validado por rol.

## Bloque D (Día 9-10) - Calidad y evidencia

- Pruebas funcionales por HU Sprint 2.
- 5+ consultas SQL verificadas.
- Colección Postman exportada al repo.
- Burndown + Review + Retrospectiva.
- Informe técnico APA7.

## 5) Criterios de éxito Sprint 2

Se considera Sprint 2 completo cuando:

1. 33 SP implementados o justificados con alcance aceptado por PO.
2. 3+ CRUD completos con paginación, validación y borrado lógico.
3. 5+ consultas SQL complejas ejecutables y documentadas.
4. Postman completo y exportado al repositorio.
5. Burndown real vs ideal y ceremonias documentadas.
6. Informe APA 7 completo con anexos técnicos.

