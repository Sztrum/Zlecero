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

Status: in progress.

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

Status: pending.

### Stage 3: Customers

ClickUp tasks:

- `86cavjnv9` - Baza klientów.

Planned output:

- customer/contact model and API;
- React customer list, create/edit/detail views.

Status: pending.

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

Status: pending.

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

## Work Entries

### 2026-07-25 22:02 - Stage 1 Started

- Read ClickUp project `wyceniator`.
- Confirmed MVP scope and first technical-decision task.
- Created branch `feature/mvp-execution-foundation` in both repositories.
- Created this execution log as the shared MVP work record.

Verification: pending.
