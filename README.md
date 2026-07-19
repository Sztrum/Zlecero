# Zlecero

Zlecero is a Laravel-based project for managing service and production inquiries, offers, orders, customer communication, and related files in one operational workflow.

The product is aimed at companies that still coordinate work through email, phone calls, spreadsheets, and manual status tracking. The first target niche is print shops and advertising-production businesses, including vehicle wrapping, signage, foil, banners, and similar custom production services.

## Product Workflow

The core business process is:

```txt
customer email
-> inquiry
-> offer / estimate
-> customer approval
-> order
-> production status
-> contact history
```

The first product version is intended to cover:

- authentication,
- organizations and users,
- customers,
- mailbox import,
- inquiry creation from emails,
- attachments,
- statuses,
- notes,
- customer replies from the panel,
- manual offer creation,
- offer sending,
- offer approval through a secure link,
- order creation after offer approval.

The first version is not intended to become a full ERP, warehouse system, invoicing system, accounting system, or advanced pricing engine.

## Repository Scope

This repository contains the Laravel backend, public API foundation, persistence layer, queues, email-related infrastructure, and Laravel-served public/SEO frontend assets.

The client-facing application UI and admin UI are planned as a separate React project. This repository should keep API contracts and backend behavior ready for that future frontend without depending on React-side implementation details.

## Current Implementation

The application is structured as a modular monolith under `app/V1/**`.

Current shared areas:

- `app/V1/Core/**` for framework integration, command bus, base providers, shared HTTP foundations, translations, exceptions, and package adapters.
- `app/V1/Shared/**` for shared migrations, value objects, traits, DTOs, scopes, requests, and resources.

Current modules:

- `Auth` for login, registration, password reset, email verification, token responses, and authentication routes.
- `User` for user persistence, registration handling, user events, user aggregate rules, and user email notifications.
- `Country` for country configuration, country resources, validation rules, and country lookup behavior.
- `Email` for shared email-sending service registration.

The main architectural direction is:

```txt
UI -> Application -> Domain
Infrastructure = adapters
```

Write-side behavior should follow command-based flow: controllers dispatch commands, and command handlers own the side effects. Domain validation belongs in aggregates or domain services.

## Technology Stack

- Backend/API: Laravel 11, PHP 8.3+
- Database: MySQL or a compatible database
- Authentication: Laravel Sanctum
- Debugging/inspection: Laravel Telescope
- Queues/cache/session storage: Laravel database drivers by default, with Redis available in the local Docker setup
- Frontend assets in this repository: Laravel Blade, TypeScript, SCSS, Tailwind, Vite
- Planned client/admin UI: separate React, TypeScript, Tailwind, Vite project

## Requirements

- PHP 8.3+
- Composer
- Node.js 22+
- MySQL or compatible database
- Redis when using the Laradock setup or Redis-backed local services

## Quick Start

1. Install dependencies:

   ```bash
   composer install
   npm install
   ```

2. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

3. Adjust database, app URL, mail, Telescope, Redis, and any local service settings in `.env`.

4. Generate the application key:

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

## Useful Commands

```bash
php artisan test
npm run prod
vendor/bin/ecs check app
vendor/bin/ecs check app --fix
```

## Laradock Configuration

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
   PHP_VERSION=8.3
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

   ```dotenv
   # Windows
   DOCKER_SYNC_STRATEGY=unison

   # macOS
   DOCKER_SYNC_STRATEGY=native_osx
   ```

7. Set the project-specific values:

   ```dotenv
   COMPOSE_PROJECT_NAME=zlecero
   DATA_PATH_HOST=~/.laradock/zlecero
   ```

8. Start the containers:

   ```bash
   sh start.sh
   ```

## Notes

- Telescope is enabled by default in `.env.example`.
- `FRONTEND_APP_URL` points to the future separate frontend application.
- The current repository is the source of truth for backend, API, persistence, queues, mail, and Laravel-served public frontend concerns.
