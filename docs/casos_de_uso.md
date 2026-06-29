# Casos de Uso (resumen)

## Actores

- Administrador
- Mesero
- Cajero
- Cocinero

## Casos de uso principales

1. **CU-01** Iniciar sesión
2. **CU-02** Cerrar sesión
3. **CU-03** Registrar usuario (empleado)
4. **CU-04** Editar usuario
5. **CU-05** Registrar producto
6. **CU-06** Asociar insumos a producto
7. **CU-07** Ver menú
8. **CU-08** Consultar inventario
9. **CU-09** Actualizar stock manual
10. **CU-10** Registrar pedido
11. **CU-11** Cambiar estado de pedido
12. **CU-12** Registrar venta
13. **CU-13** Generar factura
14. **CU-14** Consultar ventas
15. **CU-15** Visualizar dashboard

## Relación actor-caso

- **Administrador**: CU-01..15 (excepto funciones operativas exclusivas por rol)
- **Mesero**: CU-01, CU-02, CU-07, CU-10, CU-11, CU-15
- **Cajero**: CU-01, CU-02, CU-07, CU-12, CU-13, CU-14, CU-15
- **Cocinero**: CU-01, CU-02, CU-11, CU-15

