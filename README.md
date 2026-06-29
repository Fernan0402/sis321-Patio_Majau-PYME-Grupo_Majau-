# Sistema de Ventas - Restaurante Patio del Majau

Proyecto académico de **Desarrollo de Sistemas II** para digitalizar la operación del restaurante: autenticación, usuarios, productos, inventario, pedidos, ventas y facturación.

## Stack tecnológico

- **Backend:** Laravel (PHP 8.x)
- **Frontend:** Blade + Bootstrap + Vite
- **Base de datos:** MySQL (XAMPP)
- **Versionado:** Git + GitHub

## Módulos implementados (Sprint 1)

- **HU-01:** Inicio de sesión (usuario/contraseña, validación, logout, redirección por rol)
- **HU-08:** Registro y gestión de usuarios (empleados)
- **HU-14:** Registro de productos
- **HU-13:** Menú por categorías con productos activos
- **HU-19:** Inventario con alertas de stock bajo y actualización manual
- **HU-02:** Registro de pedidos con detalle y validación de stock
- **HU-03:** Registro de ventas
- **HU-17:** Generación de factura con numeración única, detalle e impresión

## Seguridad y control de acceso

- Middleware `auth` en rutas internas.
- Middleware `role` para perfiles:
  - **Administrador:** usuarios, productos, inventario, ventas, pedidos
  - **Mesero:** pedidos
  - **Cajero:** ventas y facturas
  - **Cocinero:** actualización de estado de pedidos

## Base de datos

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

Seeders disponibles:

- `EmpleadoSeeder`
- `MesaSeeder`
- `InsumoSeeder`
- `ProductoSeeder`
- `ProductoInsumoSeeder`

## Instalación y ejecución

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

Acceso local: `http://127.0.0.1:8000`

## Verificación rápida

```bash
php artisan route:list
php artisan test
```

## Rutas principales

- `/login`
- `/dashboard`
- `/empleados`
- `/productos`
- `/menu`
- `/inventario`
- `/pedidos`
- `/ventas`
- `/ventas/{venta}/factura`

## Estructura documental

- `INFORME_DESARROLLO_SISTEMA_WEB.md`
- `RETROSPECTIVA_SPRINT1.md`
- `AGENTS.md`
- `DEPLOY.md`
- `docs/ROADMAP.md`
- `docs/arquitectura.md`
- `docs/backlog.md`
- `docs/casos_de_uso.md`
- `docs/modelo_datos.md`
- `docs/diagramas_uml.md`
- `docs/guia_entrevista.md`
- `docs/DESIGN.md`

## Flujo de demo recomendado

1. Iniciar sesión según rol.
2. (Admin) Registrar usuario.
3. (Admin) Registrar producto y asociar insumos.
4. Ver menú.
5. (Mesero/Admin) Registrar pedido.
6. Cambiar estado del pedido hasta `Entregado`.
7. (Cajero/Admin) Registrar venta.
8. Visualizar/imprimir factura.
