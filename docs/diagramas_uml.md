# Diagramas UML (guía del proyecto)

## 1) Diagrama de clases

Clases núcleo:

- Empleado
- Producto
- Insumo
- Mesa
- Pedido
- DetallePedido
- Venta
- Factura

## 2) Diagrama de casos de uso

Actores:

- Administrador
- Mesero
- Cajero
- Cocinero

Casos principales:

- Login, gestionar usuarios, gestionar productos
- Registrar pedido, registrar venta, generar factura
- Consultar inventario y alertas de stock

## 3) Diagrama de actividad (pedido -> venta)

1. Mesero registra pedido
2. Sistema valida stock
3. Cocina cambia estado
4. Pedido entregado
5. Cajero registra venta
6. Sistema genera factura

## 4) Diagrama de secuencia (venta/factura)

- Cajero -> VentaController -> Pedido/Venta/Factura -> DB -> Vista factura

## 5) Diagrama de despliegue

- Navegador web (cliente)
- Servidor Laravel/PHP
- MySQL (XAMPP o producción)

