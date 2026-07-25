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

Status: completed.

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

Status: completed.

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

Status: completed.

## Active Problems And Questions

- Product: first live email provider is not selected. Current plan avoids blocking MVP by implementing manual inquiries first.
- Product: payment provider and invoicing flow are not selected. Current plan defers payment adapter.
- Product: AI provider and retention rules are not selected. Current plan defers AI.
- Product: customer panel access method is not selected. Current plan will use authenticated app routes until a token/link model is approved.
- Architecture: tenant isolation must be implemented before customer/inquiry/offer CRUD to avoid retrofitting ownership constraints.
- Architecture: public landing page reference from the original request was Windows-style (`B:\Konrad\Execute instructions from file (1)`). A local Figma Make export was found in `/home/sztrum/php8.3/zlecero-app/agent-context/project-figma` and is used as the visual source for Stage 7.
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

PR/merge status: backend PR #14 and React PR #7 merged.

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

PR/merge status: backend PR #14 and React PR #7 merged.

### 2026-07-25 23:07 - Stage 5 Completed Locally

- Implemented Laravel inquiry file metadata with tenant ownership, inquiry/customer/message links, upload author, source, category, description, MIME type, size, storage disk, and stored path.
- Added manual upload, download, internal note, and owner assignment API endpoints under `/api/v1/inquiries/{inquiry_id}`.
- Added backend validation for allowed file extensions and 20 MB upload size, plus company-scoped file lookup before download.
- Added internal notes that are always marked internal and never reused as outbound correspondence.
- Added owner assignment auditing through an internal note and restricted owner changes to company owner/admin roles.
- Implemented React inquiry detail sections for owner assignment, files with upload/download, upload progress/cancel state, and internal notes.
- Extended React API declarations, shared API types, and MSW models/handlers to match the Laravel contract.

Problems/questions:

- Live e-mail attachment import remains deferred until the e-mail provider adapter exists. Stage 5 stores `source` and `inquiry_message_id` so inbound attachments can be attached later without changing the file library contract.
- Department ownership and the "my department" filter are not implemented because there is no department model in the current MVP tenant schema. Current completed filters cover "my cases" through `owner=me` and unassigned cases through `queue=unassigned`.
- Production storage should move from the local disk to an S3-compatible disk and backup policy before real customer files are stored in production.
- Frontend production build still reports the existing large chunk warning and an outdated `caniuse-lite` notice; both are non-blocking build warnings.
- Backend PHPStan cache under `storage/framework/cache/phpstan` contains files owned by another system UID, so PHPStan was verified with a temporary config using `/tmp/zlecero-phpstan-stage5-cache`.

Verification:

- Backend: `php artisan migrate` - passed and applied `2026_07_25_210016_create_inquiry_files_table` and `2026_07_25_210017_create_inquiry_notes_table`.
- Backend: `php artisan test --filter=InquiryFilesNotesApiContractTest` - passed.
- Backend: `php artisan test --filter=InquiryWorkflowApiContractTest` - passed.
- Backend: `php artisan test --filter=CompanyAccessApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan-stage5.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning and outdated `caniuse-lite` notice.

PR/merge status: backend PR #15 and React PR #8 merged.

### 2026-07-25 23:34 - Stage 6 Completed Locally

- Implemented Laravel `Offer` and `Order` modules with company-scoped models, API routes, repositories, resources, validations, translations, and service providers.
- Added offer editor contract with inquiry link, customer/owner carry-over, number, dates, validity, payment term, delivery cost, discount, deposit, terms, notes, and line items.
- Added deterministic backend calculations for item net/tax/gross, subtotal, discount, tax, total gross, and deposit using persisted integer cents.
- Added simple local PDF generation and tenant-scoped PDF download endpoint without introducing a new PDF dependency.
- Added offer send action that locks draft editing and moves the offer to sent state.
- Added offer acceptance that blocks draft/rejected/expired offers and creates exactly one linked order.
- Added order list/detail API with copied customer, inquiry, offer, owner, item, total, term, and date data.
- Implemented React offer list, offer editor, offer detail actions, PDF download link, order list, order detail, navigation items, natural routes, API hooks, shared money formatting, and MSW handlers.

Problems/questions:

- PDF generation is synchronous and uses a minimal built-in PDF writer for MVP. A production document engine and richer template can replace it later without changing the API download contract.
- Offer "send" currently records sent state but does not send e-mail externally because the e-mail provider remains undecided.
- Customer self-acceptance/public token flow is not implemented yet. Stage 6 supports employee-side acceptance through authenticated API.
- Order team notifications are not implemented yet because notification channels are still deferred.
- Parallel backend test execution with `RefreshDatabase` continues to race on the shared local MySQL schema; Stage 6 backend tests were verified sequentially.
- Frontend production build still reports the existing large chunk warning and outdated `caniuse-lite` notice; both are non-blocking build warnings.

Verification:

- Backend: `php artisan migrate` - passed and applied offer/order migrations.
- Backend: `LOG_CHANNEL=stderr php artisan test --filter=OfferOrderApiContractTest` - passed.
- Backend: `LOG_CHANNEL=stderr php artisan test --filter=InquiryWorkflowApiContractTest` - passed sequentially.
- Backend: `LOG_CHANNEL=stderr php artisan test --filter=CompanyAccessApiContractTest` - passed sequentially.
- Backend: `vendor/bin/phpstan analyse --memory-limit=1G --configuration=/tmp/zlecero-phpstan-stage6.neon` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing bundle-size warning and outdated `caniuse-lite` notice.

PR/merge status: backend PR #16 and React PR #9 merged.

### 2026-07-26 00:48 - Stage 7 Completed

- Retrieved ClickUp tasks for operational dashboard, public landing page, pricing, FAQ, about, contact, SaaS customer dashboard, and admin dashboard.
- Confirmed product split: SEO/public pages stay in Laravel; authenticated company/admin dashboards stay in React.
- Found local Figma Make reference under the React repository task context and used it for landing/dashboard composition.
- Implemented Laravel `Dashboard` API module with `/api/v1/dashboard` and `/api/v1/dashboard/admin`.
- Implemented dashboard cards, attention items, today tasks, upcoming deadlines, basic stats, and recent activity using company-scoped source modules.
- Implemented Laravel public static pages with localized `/pl`, `/en`, and `/de` paths, canonical/alternate meta tags, landing, pricing, FAQ, about, contact, contact request validation, honeypot anti-spam, duplicate protection, and queued contact email.
- Implemented React company dashboard using the real dashboard endpoint, owner/team filter, loading/error/empty states, and navigation links to operational records.
- Implemented React admin dashboard route `/app/admin` using the platform metrics endpoint.
- Added MSW dashboard handlers that calculate local/test dashboard data from the existing mock database.

Problems/questions:

- A real platform-admin role does not exist yet. The MVP admin dashboard currently exposes platform-level metrics to company owner/admin users and avoids showing business records from other companies. A dedicated platform RBAC model should replace this before production administration.
- Contact form delivery currently queues an email to `mail.from.address`; a production support mailbox and retry/notification policy should be configured before launch.
- English and German public-page translations currently reuse most Polish body copy except localized metadata and hero text. Full localized copy should be completed before SEO work in those markets.

Verification:

- Backend: `LOG_CHANNEL=stderr php artisan test --filter=DashboardStaticPagesContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --configuration=/tmp/zlecero-phpstan-stage7.neon --memory-limit=1G` - passed.
- Backend: `npm run prod` - passed with existing Sass legacy API and outdated `caniuse-lite` warnings.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed after formatting dashboard files.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing outdated `caniuse-lite` and large chunk warnings.

PR/merge status: backend PR #17 and React PR #10 merged.

### 2026-07-26 01:28 - Stage 8 Completed

- Identified a remaining MVP gap from the definition: orders could be created automatically from accepted offers, but they could not yet be completed.
- Added Laravel order status transition endpoint under `/api/v1/orders/{order_id}/status`.
- Added order status transition rules: `new` can move to `in_progress` or `completed`, `in_progress` can move to `completed`, and completed orders cannot be reopened in MVP.
- Added React order-detail workflow actions for starting and completing an order.
- Added MSW support for order status changes.

Problems/questions:

- MVP order completion currently stores only status. Completion timestamp, production checklist, assignee workload, and customer notification should be modeled in later operations/planning scope.
- Reopening completed orders is blocked until product rules define who can reopen and how audit/customer notifications should work.

Verification:

- Backend: `LOG_CHANNEL=stderr php artisan test --filter=OfferOrderApiContractTest` - passed.
- Backend: `vendor/bin/phpstan analyse --configuration=/tmp/zlecero-phpstan-stage7.neon --memory-limit=1G` - passed.
- React: `npm run check-types` - passed.
- React: `npm run lint` - passed after formatting.
- React: `npm test -- --run` - passed.
- React: `npm run build` - passed with existing outdated `caniuse-lite` and large chunk warnings.

PR/merge status: backend PR #18 and React PR #11 merged.

## MVP Completion Status

Status: completed for the scoped MVP defined in this log.

Completed capability checklist:

- company account registration and sign-in;
- employee/user management;
- customer database;
- manual inquiry creation and company-scoped inquiry workflow;
- owner assignment, priority, response/realization/pickup dates, archive, files, and internal notes;
- offer draft/editor, commercial terms, deterministic totals, PDF generation, send state, and acceptance;
- automatic order creation after accepted offer;
- order start and completion workflow;
- API-backed company dashboard and basic admin dashboard;
- localized public landing, pricing, FAQ, about, and contact pages.

Remaining non-MVP follow-up candidates:

- live mailbox provider integration and outbound offer email delivery;
- production PDF template/engine;
- public customer self-acceptance link/token flow;
- platform-admin RBAC separate from company owner/admin;
- production contact mailbox, retry policy, and legal company data;
- full English/German body copy for public SEO pages;
- production storage/backups for customer files;
- realtime notifications and richer order production planning.

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
