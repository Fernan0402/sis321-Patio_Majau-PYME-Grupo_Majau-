# Sistema de Ventas - Restaurante Patio del Majau

## 1) Descripción

Proyecto académico de **Desarrollo de Sistemas II** para digitalizar el flujo operativo del restaurante Patio del Majau: usuarios, productos, menú, pedidos, ventas y facturación.

Este repositorio contiene la implementación del **Sprint 1** priorizado por MoSCoW.

## 2) Stack tecnológico (Paso 1)

- **Backend**: PHP 8.x + Laravel (base del proyecto en Laravel 12, compatible con enfoque Laravel 11)
- **Base de datos**: MySQL (XAMPP)
- **Frontend**: Blade + Bootstrap
- **Entorno local**: XAMPP y Artisan (`php artisan serve`)

## 3) Base de datos (Paso 2)

- Motor usado: **MySQL de XAMPP**
- Diseño basado en el DER derivado del diagrama de clases de Actividad 2
- Normalización aplicada en diseño:
  - 1FN: atributos atómicos
  - 2FN: separación de entidades y eliminación de dependencias parciales
  - 3FN: eliminación de dependencias transitivas
- Se implementaron migraciones con constraints (PK, FK, unique, enums)

Tablas principales:

- `empleados`
- `productos`
- `insumos`
- `producto_insumo`
- `mesas`
- `pedidos`
- `detalle_pedidos`
- `ventas`
- `facturas`

## 4) Datos de prueba (Paso 3)

Se puede cargar datos de prueba con seeders:

```bash
php artisan migrate
php artisan db:seed
```

Seeders incluidos:

- `EmpleadoSeeder`
- `MesaSeeder`
- `InsumoSeeder`
- `ProductoSeeder`
- `ProductoInsumoSeeder`

> Nota: Se puede complementar con Mockaroo (nombres LATAM, ciudades, etc.) exportando CSV/SQL para ampliar volumen de pruebas.

## 5) Historias de Usuario del Sprint 1 implementadas

Estado actual (HU-01 temporalmente en pausa por incidencia de sesión/rutas en entorno local):

- HU-08: Registrar usuarios
- HU-14: Registrar productos
- HU-13: Ver menú
- HU-19: Notificar falta de stock
- HU-02: Registrar pedido
- HU-17: Generar factura
- HU-03: Registrar venta

## 6) Rutas clave para demo

- `/dashboard`
- `/empleados`
- `/productos`
- `/menu`
- `/pedidos`
- `/ventas`
- `/ventas/{venta}/factura`

## 7) Instalación y ejecución

1. Clonar repositorio
2. Instalar dependencias
3. Configurar `.env`
4. Ejecutar migraciones y seeders
5. Levantar servidor local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

## 8) Branching strategy (recomendado)

- `main`: rama estable
- `develop`: integración del sprint
- `feature/hu-08-usuarios`
- `feature/hu-14-productos`
- `feature/hu-13-menu`
- `feature/hu-19-stock-alertas`
- `feature/hu-02-pedidos`
- `feature/hu-03-ventas-hu17-factura`
- `docs/sprint1-informe`

## 9) Plan de commits para GitHub (5+ commits)

Sugerencia mínima de commits descriptivos:

1. `feat(hu-08): implementar CRUD de empleados para registro de usuarios`
2. `feat(hu-14): completar validaciones y vistas de gestión de productos`
3. `feat(hu-13): agregar vista de menú por categorías para cliente`
4. `feat(hu-19): incorporar alertas de stock bajo en dashboard y pedidos`
5. `feat(hu-02): implementar registro de pedidos con detalle y cambio de estado`
6. `feat(hu-03,hu-17): registrar ventas y generar factura correlativa`
7. `docs(sprint1): agregar informe APA7, demo review y retrospectiva`

## 10) Sprint Review - Demo (10 min)

Flujo recomendado:

1. Crear usuario en `/empleados/create` (Administrador/Cajero/Mesero)
2. Crear producto en `/productos/create`
3. Ver menú en `/menu`
4. Registrar pedido en `/pedidos/create`
5. Actualizar estado hasta `Entregado`
6. Registrar venta en `/ventas/create`
7. Mostrar factura en `/ventas/{id}/factura`

## 11) Retrospectiva Start / Stop / Continue

| Tipo | Ítem |
|---|---|
| Start | Automatizar pruebas HTTP de flujo pedido -> venta -> factura |
| Start | Integrar carga masiva de datos Mockaroo para pruebas de rendimiento |
| Stop | Acoplar lógica de negocio directamente en controladores largos |
| Stop | Dejar rutas críticas sin pruebas de regresión antes de merge |
| Continue | Desarrollo por HU y prioridad MoSCoW |
| Continue | Uso de migraciones, seeders y commits semánticos por feature |

## 12) Entregable de informe

Revisar el documento:

- `INFORME_SPRINT1_APA7.md`

Incluye resumen ejecutivo, marco teórico con citas, desarrollo, resultados, conclusiones, plan de review, retrospectiva y referencias en formato APA 7.
