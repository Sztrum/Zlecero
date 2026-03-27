# Laravel Boilerplate Template

Laravel 13 boilerplate with a modular `app/V1` structure, basic auth-related modules, Vite frontend tooling, and optional Laradock setup.

## Requirements

- PHP 8.5+
- Composer
- Node.js 22+
- MySQL or compatible database

## Quick start

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Copy environment file:
   ```bash
   cp .env.example .env
   ```
3. Adjust database, app URL, mail, and any local service settings in `.env`.
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start local development:
   ```bash
   php artisan serve
   npm run dev
   ```

## Laradock configuration

Important: disable Secure Boot in BIOS before using this setup.

1. Add Laradock:
   ```bash
   git clone https://github.com/Laradock/laradock.git laradock
   ```
2. Copy the contents of `.laradock` into `laradock`, overwriting existing files:
   ```bash
   cp -rT .laradock laradock
   ```
3. Inside `laradock`, copy `.env.example` to `.env`.
4. In `laradock/.env`, set:
   ```dotenv
   PHP_VERSION=8.5
   PHP_WORKER_INSTALL_REDIS=true
   PHP_WORKER_INSTALL_GD=true
   PHP_FPM_INSTALL_EXIF=true
   WORKSPACE_INSTALL_IMAGEMAGICK=true
   PHP_FPM_INSTALL_IMAGEMAGICK=true
   PHP_WORKER_INSTALL_IMAGEMAGICK=true
   PHP_FPM_INSTALL_GHOSTSCRIPT=true
   PHP_WORKER_INSTALL_GHOSTSCRIPT=true
   PHP_WORKER_INSTALL_ZIP_ARCHIVE=true
   WORKSPACE_INSTALL_SOAP=true
   PHP_FPM_INSTALL_SOAP=true
   PHP_WORKER_INSTALL_SOAP=true
   PHP_FPM_INSTALL_OPCACHE=false
   WORKSPACE_AST_VERSION=1.1.3
   WORKSPACE_NODE_VERSION=22
   ```
5. Update container user IDs to avoid clashes with the base image:
   ```dotenv
   WORKSPACE_PUID=1001
   WORKSPACE_PGID=1001
   PHP_FPM_PUID=1002
   PHP_FPM_PGID=1002
   PHP_WORKER_PUID=1003
   PHP_WORKER_PGID=1003
   LARAVEL_HORIZON_PUID=1004
   LARAVEL_HORIZON_PGID=1004
   ```
6. Set the Docker sync strategy according to your OS:
   - Windows: `DOCKER_SYNC_STRATEGY=unison`
   - macOS: leave `DOCKER_SYNC_STRATEGY=native_osx`
7. Set the project-specific values:
   ```dotenv
   COMPOSE_PROJECT_NAME=reportseller-api
   DATA_PATH_HOST=~/.laradock/reportseller-api
   ```
8. Start the containers:
   ```bash
   sh start.sh
   ```

## Notes

- Telescope is enabled by default in `.env.example`.
- The repository is intended to be used as a starter template for client projects.
- Before publishing as a public template, review `.env`, branding, mail settings, and any project-specific documentation.
