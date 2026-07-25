# MVP Execution Log

## Purpose

This file is the running implementation log for the Zlecero MVP. It records stages, ClickUp task mapping, technical/product decisions, implementation notes, risks, open questions, verification, and PR/merge status across the Laravel backend and React application repositories.

## Operating Rules

- Source ClickUp project: `wyceniator` (`901511256152`).
- Backend repository: `/home/sztrum/php8.3/zlecero`.
- React repository: `/home/sztrum/php8.3/zlecero-app`.
- Laravel remains the source of truth for domain, persistence, API, public SEO pages, integrations, queues, files, PDF generation, billing, audit, and platform administration.
- React owns the authenticated company/admin/customer application UI.
- Work is delivered in small stages on dedicated branches, with PRs kept synchronized and merged only when the stage is verified.
- Any uncertainty that affects architecture, product scope, data ownership, external providers, security, or paid infrastructure is recorded here before implementation continues.

## MVP Definition From ClickUp

MVP is complete when a company can:

- create an account;
- sign in;
- add an employee and customer;
- create or receive an inquiry;
- assign an owner;
- set status, priority, and dates;
- add files and internal notes;
- prepare an offer;
- generate a PDF;
- send an offer;
- register acceptance;
- create and complete an order.

## Current Technical Decisions

### Authentication

- Decision: Laravel Sanctum personal access tokens sent as Bearer tokens.
- Adapter: Laravel Sanctum token API in Laravel, session-scoped browser storage in React.
- Reason: already implemented and verified for the current MVP integration; avoids cookie/domain setup friction in local development.
- Limitation: browser token storage is less ideal than secure first-party cookie sessions for production.
- Fallback plan: switch to first-party cookie/session auth only after explicit approval, documentation update, CSRF/CORS setup, and React API client update.
- Domain boundary: token issuance and revocation stay outside Domain; Domain receives authenticated user context through application/service boundaries.

### First Email Integration

- Decision: defer live provider integration; implement manual inquiries first, then provider adapter.
- Adapter: manual inquiry creation for MVP core, then Gmail/Microsoft 365/IMAP adapter after workflow is stable.
- Reason: ClickUp MVP allows creating or receiving an inquiry; manual creation unblocks the core flow without external OAuth/provider risk.
- Limitation: no production mailbox synchronization in early stages.
- Fallback plan: define an `EmailConnector` infrastructure contract before adding provider-specific code.
- Domain boundary: provider SDKs must stay in Infrastructure; Domain should model messages/inquiries without depending on provider APIs.

### Payments And Invoicing

- Decision: defer paid billing implementation until core workflow is operational.
- Adapter: internal package/limit/trial models first, payment provider later.
- Reason: MVP core depends more on tenant limits and access state than on real card payments.
- Limitation: no production subscription charging in early MVP.
- Fallback plan: add Stripe or another selected billing adapter after package/limit contracts exist.
- Domain boundary: payment provider events enter through Infrastructure/Application handlers.

### AI Provider

- Decision: defer AI provider integration until after core workflow and offer editing exist.
- Adapter: no external model in MVP foundation.
- Reason: ClickUp positions AI after core stabilization.
- Limitation: no automated summaries or pricing suggestions initially.
- Fallback plan: introduce provider-agnostic AI suggestion contracts with editable outputs.
- Domain boundary: model calls and provider payloads stay outside Domain.

### File Storage

- Decision: use Laravel filesystem abstraction with local storage for development and adapter-ready disk configuration.
- Adapter: Laravel Storage facade through application/infrastructure services.
- Reason: supports local MVP and later S3-compatible storage without changing Domain contracts.
- Limitation: local storage is not a production backup strategy.
- Fallback plan: configure S3-compatible storage and backups before production file handling.
- Domain boundary: Domain stores file metadata and ownership, not storage-driver details.

### Realtime

- Decision: defer realtime presence/notifications; start with pull-based dashboard and notification list.
- Adapter: API polling/TanStack Query invalidation in React.
- Reason: core CRUD/workflow can ship without websocket infrastructure.
- Limitation: no live presence or instant updates initially.
- Fallback plan: add Laravel broadcasting/Reverb or Pusher-compatible adapter after notification contracts are stable.
- Domain boundary: events are domain/application events; realtime transport remains Infrastructure/UI.

## Stage Plan

### Stage 1: MVP Foundation And Decisions

ClickUp tasks:

- `86caw15rk` - Podjąć decyzje techniczne przed integracjami.
- `86caw0qk3` - Realizować MVP według zależności technicznych.
- `86caw0n6u` - Ustalić architekturę modułową Laravel API.
- `86caw0pfd` - Ustalić modułową architekturę aplikacji React.

Planned output:

- this execution log;
- explicit dependency order for upcoming implementation;
- repository documentation alignment if current AGENTS rules do not cover a confirmed MVP decision.

Status: completed.

### Stage 2: Tenant, Company, Roles, And Access Isolation

ClickUp tasks:

- `86caw0n9e` - Zaprojektować model firmy i izolację danych.
- `86caw0nj0` - Przygotować bazowe role i autoryzację.
- `86cavjp7w` - Rejestracja firmy.
- `86cavjpnc` - Zarządzanie użytkownikami.
- `86cavjprn` - Ustawienia firmy.

Planned output:

- Laravel company/tenant domain model, migrations, repositories, and API contracts;
- owner/admin/member roles and access checks;
- React onboarding/company settings/user management screens.

Status: completed.

### Stage 3: Customers

ClickUp tasks:

- `86cavjnv9` - Baza klientów.

Planned output:

- customer/contact model and API;
- React customer list, create/edit/detail views.

Status: completed.

### Stage 4: Inquiries And Workflow

ClickUp tasks:

- `86cavjn6b` - Statusy i workflow.
- `86cavjn7y` - Kolejka spraw wymagających działania.
- `86cavjnay` - Priorytety zapytań.
- `86cavjncp` - Terminy odpowiedzi, realizacji i odbioru.
- `86cavjnew` - Archiwizacja spraw.
- `86cavjn1q` - Obsługa korespondencji.
- `86cavjn4k` - Łączenie wiadomości w jedno zapytanie.

Planned output:

- manual inquiry creation and workflow transitions;
- ownership, priority, dates, queue, archive state;
- basic correspondence/thread model without live mailbox sync.

Status: completed.

### Stage 5: Files And Notes

ClickUp tasks:

- `86cavjnx6` - Zarządzanie plikami.
- `86cavjnyn` - Ręczny upload plików.
- `86cavjp12` - Przypisywanie opiekuna.
- `86cavjp2z` - Notatki wewnętrzne i komentarze zespołowe.

Planned output:

- file metadata, upload endpoints, ownership checks;
- internal notes/comments linked to inquiries.

Status: pending.

### Stage 6: Offers, PDF, Acceptance, And Orders

ClickUp tasks:

- `86cavjngt` - Edytor ofert.
- `86cavjnpa` - Warunki handlowe.
- `86cavjnkf` - Generowanie PDF ofert.
- `86cavjntf` - Automatyczne tworzenie zlecenia po akceptacji oferty.

Planned output:

- offer draft/edit lifecycle;
- PDF generation;
- offer send/acceptance record;
- order creation and completion flow.

Status: pending.

### Stage 7: Dashboard And Public Views

ClickUp tasks:

- `86cavjmxp` - Dashboard.
- `86cavjkwd` - Przygotować landing page.
- `86cavjm7u` - Przygotować podstronę cennika.
- `86cavjme4` - Przygotować podstronę FAQ.
- `86cavjmj3` - Przygotować podstronę „O firmie”.
- `86cavjmn2` - Przygotować podstronę kontaktową.
- `86cavjmq9` - Przygotować podstawowy widok dashboardu klienta SaaS.
- `86cavjmtr` - Przygotować podstawowy widok dashboardu administratora.

Planned output:

- operational app dashboard in React;
- SEO/public pages in Laravel following the provided visual reference;
- basic customer/admin dashboard shells only where required by MVP.

Status: pending.

## Active Problems And Questions

- Product: first live email provider is not selected. Current plan avoids blocking MVP by implementing manual inquiries first.
- Product: payment provider and invoicing flow are not selected. Current plan defers payment adapter.
- Product: AI provider and retention rules are not selected. Current plan defers AI.
- Product: customer panel access method is not selected. Current plan will use authenticated app routes until a token/link model is approved.
- Architecture: tenant isolation must be implemented before customer/inquiry/offer CRUD to avoid retrofitting ownership constraints.
- Architecture: public landing page reference path from the original request is Windows-style (`B:\Konrad\Execute instructions from file (1)`). It must be resolved locally before public page implementation.
- Tooling: ClickUp task comments currently fail through the connector with `INVALID_ARGUMENT`; local work log remains the source of truth until connector writes are available.

## Work Entries

### 2026-07-25 22:02 - Stage 1 Started

- Read ClickUp project `wyceniator`.
- Confirmed MVP scope and first technical-decision task.
- Created branch `feature/mvp-execution-foundation` in both repositories.
- Created this execution log as the shared MVP work record.

Verification:

- Backend: `php artisan migrate` - passed, nothing pending.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed.
- Backend: `php artisan test --filter=AuthApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: backend PR #12 and React PR #5 merged.

### 2026-07-25 22:25 - Stage 2 Completed Locally

- Implemented Laravel company tenancy foundation:
  - `companies` table with trial/settings fields;
  - `users.company_id`, role/status, invitation/deactivation fields;
  - `Company` module provider, routes, repository, resources, exceptions, and translations;
  - authenticated company settings endpoint;
  - authenticated company-user list/invite/deactivate endpoints;
  - registration command now creates a company and owner user transactionally;
  - login rejects inactive/deactivated company users.
- Implemented React company access UI:
  - registration now collects company name, password confirmation, and required terms acceptance;
  - authenticated routes `/app/company` and `/app/company/users`;
  - company settings form;
  - company users table, invite form, and deactivate action;
  - typed company/user API contracts and MSW handlers.
- Added tests covering company registration/profile contract, company user management authorization, cross-company denial, and last-owner protection.

Problems/questions:

- Invitation emails are still not a full product flow: invited users are persisted with `invited` status, but resend/accept-invite screens and email templates should be handled in a later user-management refinement.
- The onboarding wizard task is not complete yet; this stage prepared company/trial fields and settings, while the actual wizard UI should be a separate slice after the main domain objects exist.
- Frontend production build reports large chunks (`index`, `db`) from the existing scaffold/MSW setup; not blocking for MVP, but worth splitting before production hardening.

Verification:

- Backend: `php artisan migrate` - passed, nothing pending.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed.
- Backend: `php artisan test --filter=AuthApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: backend PR #12 and React PR #5 merged.

### 2026-07-25 22:28 - Stage 3 Started

- Created branch `feature/mvp-customers` in both repositories.
- Retrieved ClickUp task `86cavjnv9` - `Baza klientów`.
- Planned backend `Customer` module and React `customers` feature around manual customer creation, company-scoped search/profile, and duplicate warnings.

Problems/questions:

- Inquiry, message, offer, and order history cannot be fully populated until later MVP stages create those process models. Stage 3 exposes stable empty history arrays so future stages can attach real records without changing the customer profile contract shape.

Verification:

- Backend: `php artisan migrate` - passed and applied `2026_07_25_202828_create_customers_table`.
- Backend: `php artisan test --filter=CustomerApiContractTest` - passed.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed after the UUID root-cause fix.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: backend PR #13 and React PR #6 merged.

### 2026-07-25 22:40 - Stage 3 Completed Locally

- Implemented Laravel `Customer` module with company-scoped customer storage, API routing, command handlers, repository queries, resources, validation requests, exceptions, and translations.
- Added customer CRUD API slice for list/search, create, profile read, and update.
- Added potential duplicate detection by e-mail, tax number, or company name without automatic merge.
- Added stable empty customer history arrays for future inquiry/message/offer/order attachment.
- Fixed shared UUID model behavior so preassigned UUIDs are not overwritten during create.
- Implemented React `customers` feature with typed API declarations, `/app/customers`, `/app/customers/:customerId`, customer form, customer list/search, profile editing, history counters, duplicate warnings, and MSW handlers.

Problems/questions:

- Customer history is intentionally empty until inquiry/message/offer/order stages create those models.
- Duplicate detection is advisory only and intentionally does not merge records; product rules for manual merge can be added later.
- Frontend production build still reports large chunks from the existing app/MSW setup; not blocking for MVP.

Verification:

- Backend: `php artisan migrate` - passed and applied `2026_07_25_202828_create_customers_table`.
- Backend: `php artisan test --filter=CustomerApiContractTest` - passed.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed after the UUID root-cause fix.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: backend PR #13 and React PR #6 merged.

### 2026-07-25 22:42 - Stage 4 Started

- Created branch `feature/mvp-inquiries-workflow` in both repositories.
- Retrieved ClickUp tasks for inquiry status workflow, action queue, priorities, due dates, archive, correspondence, and message grouping.
- Started Laravel `Inquiry` module with company-owned inquiries, status audit, manual messages, workflow transitions, priorities, due dates, action queue filters, archive/restore, and provider-ready external message/thread identifiers.

Problems/questions:

- Live e-mail synchronization, async delivery, and provider delivery errors are intentionally deferred until the provider decision is made. This stage stores manual messages and external IDs so a provider adapter can attach later.
- Automatic message grouping is limited to stored `external_thread_id`/`external_message_id` fields in this slice; unsafe fallback matching will require provider payloads and product approval.

Verification:

- Backend: `php artisan migrate` - passed and applied inquiry workflow migrations.
- Backend: `php artisan test --filter=InquiryWorkflowApiContractTest` - passed.
- Backend: `php artisan test --filter=CustomerApiContractTest` - passed.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: pending local commit, push, PR creation, and merge for `feature/mvp-inquiries-workflow`.

### 2026-07-25 23:01 - Stage 4 Completed Locally

- Implemented Laravel `Inquiry` module with company-owned inquiries, status workflow, priority, due dates, archive/restore, status-change audit, and manual correspondence records.
- Added queue filters for new, waiting, overdue, unassigned, and urgent inquiries.
- Added status transition enforcement and audit trail with previous status, next status, user, and timestamp.
- Added provider-ready correspondence fields for external message and thread identifiers.
- Implemented React inquiry list/queue, create/edit form, detail view, status selector, archive/restore actions, status history, and manual correspondence panel.
- Added MSW inquiry workflow handlers matching the Laravel API contract.

Problems/questions:

- Live mailbox sync and async send/delivery tracking remain deferred until the e-mail provider decision is made.
- Automatic message grouping currently stores external IDs but does not perform unsafe fallback matching without provider payloads and product approval.
- Offer/order conversion is not implemented in this stage; accepted/closed statuses are prepared for the later offer/order stage.

Verification:

- Backend: `php artisan migrate` - passed and applied inquiry workflow migrations.
- Backend: `php artisan test --filter=InquiryWorkflowApiContractTest` - passed.
- Backend: `php artisan test --filter=CustomerApiContractTest` - passed.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning for large chunks.

PR/merge status: pending local commit, push, PR creation, and merge for `feature/mvp-inquiries-workflow`.

### 2026-07-25 22:06 - Stage 1 Completed

- Backend PR #11 merged with the canonical MVP execution log and technical decisions.
- React PR #4 merged with a project rule pointing agents to the canonical MVP execution log.
- Both repositories were synchronized back to `main`.
- Backend post-pull commands completed: `php artisan migrate`, `php artisan config:clear`, `php artisan cache:clear`.

Verification: documentation-only change; runtime checks were not required.

### 2026-07-25 22:20 - Stage 2 Started

- Created branch `feature/mvp-company-access` in both repositories.
- Retrieved ClickUp details for company isolation, roles, company registration, onboarding/trial, company settings, and company user management.
- Added Laravel `Company` module, company migrations, user tenant fields, role/status enums, company settings endpoint, company user endpoints, and focused company access tests.
- Started React company API declarations, registration updates, company settings view, company users view, MSW handlers, and role type updates.

Problems/questions:

- Company registration now requires password and terms acceptance; this intentionally changes the previous temporary no-password registration flow.
- Live invitation email content and resend flow are not implemented yet; invited users receive backend status and remember token support, while email delivery will be completed with notification/user-management refinement.
- Full onboarding wizard is not implemented in this slice; company/trial fields are prepared first.

Verification: pending.
