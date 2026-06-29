# DEPLOY.md — Despliegue del Sistema Patio Majau

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+ y npm/pnpm
- MySQL 8+ (o MariaDB compatible)
- Servidor web (Apache/Nginx) o `php artisan serve`

## Variables de entorno

1. Copiar archivo base:
   - `cp .env.example .env`
2. Configurar:
   - `APP_NAME`
   - `APP_ENV`
   - `APP_URL`
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

## Instalación

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
```

## Ejecución local

```bash
php artisan serve
```

Acceso: `http://127.0.0.1:8000`

## Verificación rápida

```bash
php artisan route:list
php artisan test
```

## Publicación (resumen)

1. Subir código al servidor.
2. Ejecutar instalación y migraciones.
3. Configurar virtual host apuntando a `public/`.
4. Ejecutar `npm run build`.
5. Validar login, pedidos, ventas y facturas.

