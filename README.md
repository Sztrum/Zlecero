# Laravel Boilerplate Template

Laravel 11 boilerplate with a modular `app/V1` structure, basic auth-related modules, Vite frontend tooling, and optional Laradock setup.

## Requirements

- PHP 8.3+
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

## Optional Laradock setup

1. Clone Laradock next to the project:
   ```bash
   git clone https://github.com/Laradock/laradock.git laradock
   ```
2. Copy local overrides:
   ```bash
   cp -rT .laradock laradock
   ```
3. Copy `laradock/.env.example` to `laradock/.env`.
4. Set a project-specific Laradock name, for example:
   ```dotenv
   COMPOSE_PROJECT_NAME=laravel_boilerplate_template
   DATA_PATH_HOST=~/.laradock/laravel_boilerplate_template
   ```
5. Start containers:
   ```bash
   sh start.sh
   ```

## Notes

- Telescope is enabled by default in `.env.example`.
- The repository is intended to be used as a starter template for client projects.
- Before publishing as a public template, review `.env`, branding, mail settings, and any project-specific documentation.
