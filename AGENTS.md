# AGENTS.md — Sistema Patio Majau

## Qué es este proyecto

Sistema web de gestión para restaurante (Patio del Majau) orientado a:

- Inicio de sesión por usuario interno
- Gestión de usuarios (empleados/roles)
- Gestión de productos y menú
- Control de stock e inventario
- Registro de pedidos, ventas y facturación

## Stack actual

| Capa | Tecnología |
|---|---|
| Backend | Laravel (PHP 8.x) |
| Frontend | Blade + Bootstrap + Vite |
| Base de datos | MySQL (XAMPP) |
| Control de versiones | Git + GitHub |

## Convenciones de trabajo

- Desarrollar por HU (Historia de Usuario).
- Priorizar por MoSCoW y Story Points.
- Mantener commits semánticos: `feat`, `fix`, `chore`, `docs`, `test`.
- No subir secretos (`.env`, credenciales).

## Flujo recomendado de ramas

- `main`: estable
- `develop`: integración
- `feature/hu-xx-descripcion`: desarrollo por HU
- `docs/*`: documentación

## Checklist previo a commit

1. `php artisan route:list` sin errores
2. `php artisan test` en verde
3. Validaciones backend presentes
4. Vistas enlazadas desde navegación
5. Documentación de la HU actualizada

