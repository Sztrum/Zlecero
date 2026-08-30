# Zlecero (backend)

Zlecero turns scattered customer enquiries into a tracked workflow: inquiry → offer → order. It targets companies that still coordinate work through email, phone calls and spreadsheets — the first niche being print shops and advertising-production businesses (vehicle wrapping, signage, foil, banners).

This repository is the **Laravel backend**: domain, persistence, API, queues, mail, and the public SEO pages. The authenticated application UI lives in a separate React repository, [`zlecero-app`](https://github.com/Sztrum/zlecero-app).

| Concern | Owner |
| --- | --- |
| Domain, persistence, API, queues, mail, files, PDF | this repository |
| Public/SEO pages (landing, pricing, FAQ, about, contact) | this repository, Blade |
| Company, admin and customer application UI | `zlecero-app` |

---

## Quick start

Assumes the Laradock containers are already running (see [Laradock setup](#laradock-setup) for a first-time install).

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DemoDataSeeder   # optional, see Demo data
php artisan serve --host=127.0.0.1 --port=8000
```

Then start the frontend from the `zlecero-app` repository (`npm run dev`). Its `.env` points at `http://127.0.0.1:8000/api/v1` by default, which matches the `artisan serve` command above.

Verify the API is reachable before debugging the frontend:

```bash
curl -s http://127.0.0.1:8000/api/v1/countries -H 'Accept: application/json' | head -c 200
```

> **On `http://zlecero.test`:** the `.env.example` sets `APP_URL=http://zlecero.test`, which assumes a hosts entry plus an nginx vhost pointing at the Laradock `nginx` container. If that vhost is missing or another local web server owns port 80, requests will hit the wrong server and the API will return 404 while the domain still answers. Confirm with the `curl` above against `zlecero.test/api/v1/countries`; if it fails, use `php artisan serve` and point the frontend at it.

---

## Demo data

`DemoDataSeeder` creates a complete company so every screen has something real to render. It is **not** registered in `DatabaseSeeder`, so a bare `php artisan db:seed` will never run it:

```bash
php artisan db:seed --class=DemoDataSeeder
```

| Account | Email | Password | Role |
| --- | --- | --- | --- |
| Owner | `demo@zlecero.test` | `password` | `owner` |
| Employee | `pracownik@zlecero.test` | `password` | `member` |

It creates the company *Reklama Wizual* with 6 customers (including a deliberate duplicate pair sharing a tax number, so duplicate detection has something to find), 6 inquiries spread across `new`, `preparing_offer`, `offer_sent`, `accepted` and `rejected`, 5 offers covering draft/sent/accepted/rejected plus one past its validity date, 1 order, inquiry messages, internal notes, status history and generated PDFs.

Re-running is idempotent: it deletes and recreates only the `zlecero-demo` company and the records owned by it, and never touches other companies. Offers are created through `OfferRepository` rather than inserted directly, so totals, tax, numbering and the offer-to-order conversion come from the same code paths as production.

---

## Architecture

A modular monolith under `app/V1/**`.

```txt
UI -> Application -> Domain
Infrastructure = adapters
```

Controllers dispatch commands; command handlers own side effects. Domain rules belong in aggregates, enums, or domain services — not in controllers. Provider SDKs and storage drivers stay in `Infrastructure`.

**Shared areas**

- `app/V1/Core/**` — framework integration, command bus, base providers, shared HTTP foundations, translations, exceptions, package adapters.
- `app/V1/Shared/**` — shared migrations, value objects, traits, DTOs, scopes, requests, resources.

**Modules** (`app/V1/Modules/**`)

| Module | Responsibility |
| --- | --- |
| `Auth` | Login, registration, password reset, email verification, Sanctum tokens |
| `User` | User persistence, registration handling, user events and notifications |
| `Company` | Tenant, company profile, company users, roles and access isolation |
| `Customer` | Customer profiles, contact data, duplicate detection |
| `Inquiry` | Inquiries, statuses, priorities, owners, messages, notes, files |
| `Offer` | Offer editor, totals, PDF generation, sending, acceptance |
| `Order` | Orders created from accepted offers, production statuses |
| `Dashboard` | Aggregated company and admin dashboard payloads |
| `StaticPages` | Public Blade pages, localized (pl/en/de), contact form |
| `Country` | Country configuration, resources, validation rules |
| `Email` | Shared email-sending service registration |

Every module owns its routes via a `*RouteServiceProvider`; there is no central `routes/` directory.

### Workflow rules worth knowing

Inquiry status transitions are defined in `InquiryStatus::allowedNextStatuses()` and are enforced, not advisory. Stages such as `preparing_offer` are intermediate steps rather than barriers: when sending or accepting an offer, `OfferRepository` walks the inquiry through every intermediate stage via `InquiryStatus::transitionPathTo()` and records each step in `inquiry_status_changes`. A target that genuinely cannot be reached — for example sending an offer for a `closed` inquiry — raises `InvalidInquiryStatusTransitionException` instead of being ignored.

---

## API

All endpoints are prefixed with `/api/v1` and authenticated with a Sanctum bearer token, except the auth endpoints themselves.

```txt
POST   /auth/register                          POST   /auth/login
GET    /auth/profile                           POST   /auth/logout
POST   /auth/forgot-password                   POST   /auth/reset-password/{token}
POST   /auth/user/{user}/new-password/{token}
POST   /auth/verify-email/{user}/email/verify/{hash}

GET    /companies/current                      PATCH  /companies/current
GET    /companies/users                        POST   /companies/users
PATCH  /companies/users/{user}/deactivate

GET    /customers                              POST   /customers
GET    /customers/{customer}                   PATCH  /customers/{customer}

GET    /inquiries                              POST   /inquiries
GET    /inquiries/{inquiry}                    PATCH  /inquiries/{inquiry}
PATCH  /inquiries/{inquiry}/status             PATCH  /inquiries/{inquiry}/owner
PATCH  /inquiries/{inquiry}/archive            PATCH  /inquiries/{inquiry}/restore
POST   /inquiries/{inquiry}/messages           POST   /inquiries/{inquiry}/notes
POST   /inquiries/{inquiry}/files              GET    /inquiries/{inquiry}/files/{file}/download

GET    /offers                                 POST   /offers
GET    /offers/{offer}                         PATCH  /offers/{offer}
POST   /offers/{offer}/pdf                     GET    /offers/{offer}/pdf/download
PATCH  /offers/{offer}/send                    POST   /offers/{offer}/accept

GET    /orders                                 GET    /orders/{order}
PATCH  /orders/{order}/status

GET    /dashboard                              GET    /dashboard/admin
GET    /countries
```

Every company-scoped endpoint resolves the company from the authenticated user. Company IDs are never accepted from the client.

---

## Testing and quality

```bash
php artisan test                 # feature + unit suite
composer phpstan                 # static analysis
vendor/bin/ecs check app         # code style
composer ecs-fix                 # code style, autofix
npm run prod                     # build public frontend assets
```

`phpunit.xml` pins the test database to sqlite `:memory:`, so `RefreshDatabase` can never touch the development database. Keep it that way — any test command that migrates must be isolated from a real database.

---

## Requirements

- PHP 8.5+
- Composer
- Node.js 20+ (the Laradock workspace image installs 22)
- MySQL 8 or compatible
- Redis (used by the Laradock setup)

Stack: Laravel 13, Sanctum for authentication, Telescope for inspection, database drivers for queues/cache/sessions by default, Blade + TypeScript + SCSS + Tailwind + Vite for the public pages.

---

## Laradock setup

First-time container setup. **Disable Secure Boot in BIOS before using this setup.**

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

Create both databases before running migrations — Telescope uses its own:

```sql
CREATE DATABASE zlecero CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE zlecero_telescope CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Services exposed by the Laradock setup: MySQL on `3306`, Redis on `6379`, phpMyAdmin on `8081`, MailHog on `8025` (all outgoing mail in local development lands there), nginx on `80`/`443`.

> The workspace container publishes ports `3000`, `3001`, `5173` and `8080`. The React dev server will therefore skip past `3000` and land on a higher port — read the URL it prints rather than assuming `localhost:3000`.

---

## Not implemented yet

Honest status, so nobody assumes more than is there:

- **Mailbox import.** Inquiries are created manually. There is no Gmail/IMAP/M365 connector; the `Email` module only registers outgoing mail.
- **Customer-facing offer approval.** `PATCH /offers/{offer}/send` flips the status to `sent` and does not email the customer, and `POST /offers/{offer}/accept` is an authenticated employee action. There is no public secure-link route for a customer to approve an offer themselves.
- **Offer PDF is a placeholder.** `OfferRepository::minimalPdf()` hand-writes a minimal PDF using the base Helvetica font. It has no layout and does not render Polish diacritics. A real templating/PDF library is still needed.
- **Billing, AI assistance and realtime updates** are deliberately deferred; no provider is integrated.
- **Platform administration** exposes aggregate counters and alerts only (`GET /dashboard/admin`). Subscriptions, payments, plans and feature flags have no backend.
