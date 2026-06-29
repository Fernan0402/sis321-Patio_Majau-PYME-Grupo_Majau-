# Modelo de Datos (resumen)

## Entidades principales

- `empleados`
- `productos`
- `insumos`
- `producto_insumo` (N:N con `cantidad_necesaria`)
- `mesas`
- `pedidos`
- `detalle_pedidos`
- `ventas`
- `facturas`

## Relaciones clave

- Empleado 1:N Pedido
- Empleado 1:N Venta
- Mesa 1:N Pedido
- Pedido 1:N DetallePedido
- Producto 1:N DetallePedido
- Producto N:N Insumo (pivot `producto_insumo`)
- Pedido 1:1 Venta
- Venta 1:1 Factura

## Diagrama rápido (Mermaid ER)

```mermaid
erDiagram
  EMPLEADOS ||--o{ PEDIDOS : registra
  EMPLEADOS ||--o{ VENTAS : procesa
  MESAS ||--o{ PEDIDOS : asigna
  PEDIDOS ||--o{ DETALLE_PEDIDOS : contiene
  PRODUCTOS ||--o{ DETALLE_PEDIDOS : item
  PRODUCTOS ||--o{ PRODUCTO_INSUMO : usa
  INSUMOS ||--o{ PRODUCTO_INSUMO : compone
  PEDIDOS ||--|| VENTAS : genera
  VENTAS ||--|| FACTURAS : emite
```

