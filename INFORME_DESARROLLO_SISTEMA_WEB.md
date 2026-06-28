# Informe de Desarrollo del Sistema Web
**Proyecto:** Sistema de Ventas - Restaurante Patio del Majau  
**Fecha:** Junio 2026  
**Tipo de documento:** Informe técnico de implementación

## 1. Introducción

El presente informe describe el desarrollo del sistema web para la gestión comercial y operativa del restaurante Patio del Majau. El objetivo principal del sistema es digitalizar procesos clave del negocio: administración de usuarios, catálogo de productos, visualización de menú, registro de pedidos, registro de ventas, facturación y alertas de inventario.

La implementación se realizó por iteraciones (Sprint 1), priorizando funcionalidades de alto valor para la operación diaria del restaurante.

## 2. Objetivo del desarrollo

Construir una aplicación web que permita:

- Centralizar la información de ventas e inventario.
- Reducir errores de registro manual en pedidos y cobros.
- Mejorar el control de stock mediante alertas de bajo inventario.
- Agilizar la atención al cliente con procesos más rápidos y trazables.

## 3. Herramientas utilizadas y por qué

### 3.1 Backend

- **PHP 8.x**
  - Lenguaje maduro, ampliamente usado en desarrollo web empresarial.
  - Compatible con entornos locales económicos como XAMPP.

- **Laravel (base del proyecto)**
  - Framework MVC que acelera el desarrollo con estructura clara.
  - Incluye migraciones, ORM (Eloquent), validación, rutas y seguridad.
  - Facilita mantenimiento por separación de responsabilidades.

### 3.2 Base de datos

- **MySQL (XAMPP)**
  - Motor relacional robusto y gratuito.
  - Adecuado para operaciones transaccionales del restaurante.
  - Integración nativa con Laravel.

- **MySQL Workbench (diseño recomendado)**
  - Herramienta visual para modelado DER.
  - Permite validar relaciones, llaves y normalización antes de codificar.

### 3.3 Frontend

- **Blade (motor de plantillas de Laravel)**
  - Integración directa con backend y rutas.
  - Permite construir vistas dinámicas con menor complejidad.

- **Bootstrap**
  - Acelera la construcción de interfaces responsivas.
  - Reduce tiempo de diseño visual con componentes predefinidos.

- **Vite / NPM**
  - Compilación de recursos frontend (CSS/JS) moderna y rápida.

### 3.4 Control de versiones

- **Git + GitHub**
  - Control de cambios, trazabilidad y respaldo remoto.
  - Trabajo por commits semánticos y ordenados por historia de usuario.

## 4. Arquitectura general del sistema

El sistema sigue una arquitectura web en capas:

1. **Capa de presentación (Frontend):** vistas Blade para formularios, listados y paneles.
2. **Capa de aplicación (Backend):** controladores Laravel con reglas de negocio y validaciones.
3. **Capa de datos:** modelos Eloquent y MySQL con tablas normalizadas y relaciones.

Esta arquitectura permite mantener el código organizado y facilita evolución por módulos.

## 5. Desarrollo del Backend

### 5.1 Estructura implementada

Se trabajó con:

- **Controladores:** `EmpleadoController`, `ProductoController`, `PedidoController`, `VentaController`, `DashboardController`.
- **Modelos:** `Empleado`, `Producto`, `Insumo`, `Mesa`, `Pedido`, `DetallePedido`, `Venta`, `Factura`.
- **Rutas:** definición REST para operaciones CRUD y rutas específicas para factura, menú y cambio de estado de pedidos.

### 5.2 Lógica funcional implementada

- Registro y edición de usuarios operativos.
- Gestión de productos del menú.
- Consulta de menú por categorías.
- Registro de pedidos con detalle (producto, cantidad, subtotal).
- Cambio de estado de pedido.
- Registro de ventas y generación de factura.
- Alertas de stock bajo para control de inventario.

### 5.3 Persistencia y validación

- Migraciones con claves primarias/foráneas.
- Validaciones de entrada en controladores para garantizar integridad.
- Uso de relaciones Eloquent (`hasMany`, `belongsTo`, `belongsToMany`) para consistencia de datos.

## 6. Desarrollo del Frontend

### 6.1 Vistas construidas

Se desarrollaron interfaces para:

- Dashboard con métricas.
- Módulo de empleados.
- Módulo de productos.
- Vista de menú.
- Módulo de pedidos.
- Módulo de ventas.
- Vista de factura.

### 6.2 Criterios de interfaz

- Formularios claros y orientados a operación rápida.
- Tabla de listados para consulta administrativa.
- Mensajes de éxito/error para retroalimentación al usuario.
- Navegación principal por módulos del negocio.

## 7. Integración backend-frontend

La integración se realizó mediante el flujo estándar de Laravel:

- Ruta -> Controlador -> Modelo -> Vista Blade.

Este enfoque permitió mantener consistencia entre datos, lógica y presentación, reduciendo complejidad de integración.

## 8. Resultados del Sprint 1

Se logró implementar el núcleo transaccional del sistema:

- Usuarios (HU-08)
- Productos (HU-14)
- Menú (HU-13)
- Alertas de stock (HU-19)
- Pedidos (HU-02)
- Ventas (HU-03)
- Factura (HU-17)

El resultado es una base funcional para operación real y para continuar con mejoras en próximos sprints.

## 9. Dificultades y decisiones técnicas

- Se identificó incidencia en autenticación/login del entorno local, por lo que se priorizó continuar con módulos críticos del negocio para no detener el avance del sprint.
- Se optó por mantener una arquitectura monolítica Laravel (en vez de dividir en múltiples servicios) para simplificar despliegue, mantenimiento y tiempos de entrega académicos.

## 10. Conclusión

El desarrollo del sistema web permitió transformar procesos manuales del restaurante en flujos digitales controlados. La selección tecnológica (Laravel + MySQL + Blade + Bootstrap) fue adecuada por su equilibrio entre rapidez de desarrollo, mantenibilidad y bajo costo operativo.

Como siguiente etapa, se recomienda fortalecer autenticación, control granular de permisos y pruebas automatizadas para consolidar la calidad del sistema antes de producción.
