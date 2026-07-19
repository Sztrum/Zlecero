# AGENTS_PROJECT_RULES.md

## Version
v1.2.0

## Scope
Repository-specific rules for the Zlecero Laravel project.

## Purpose
This file contains project-specific paths, architecture boundaries, Laravel conventions, verification commands, and module contracts. Portable agent workflow rules are defined in `AGENTS.md`; portable coding/style rules are defined in `AGENTS_CODING_RULES.md`.

## Required Document Read Order
Read documents in this exact order before implementation:
1. `AGENTS.md`
2. `AGENTS_CODING_RULES.md`
3. `AGENTS_PROJECT_RULES.md`
4. all relevant module-level `AGENTS.md` files in scope

## Project Identity And Product Split
- This repository is the Laravel project for Zlecero.
- This repository is the source of truth for backend, public API, SEO-oriented frontend served by Laravel, persistence, domain rules, queues/jobs, mail, and integration boundaries.
- A separate React project is planned for the client-facing application UI and admin UI.
- Do not implement the client application or admin panel React UI in this repository unless the user explicitly asks for temporary Laravel-side scaffolding.
- Keep SEO-critical public pages in this Laravel repository unless the user explicitly moves that responsibility elsewhere.
- Keep Laravel Blade/Vite frontend work focused on SEO/public/server-rendered needs, shared backend-adjacent assets, or explicitly requested temporary previews.
- When adding/changing API endpoints, preserve clear contracts so the future React client/admin project can consume them without depending on Blade internals.

## Repository Map
- Main Laravel implementation scope: `app/V1/**`.
- Shared architecture layers:
  - `app/V1/Core/**`
  - `app/V1/Shared/**`
- Current modules:
  - `app/V1/Modules/Auth/**`
  - `app/V1/Modules/User/**`
  - `app/V1/Modules/Country/**`
  - `app/V1/Modules/Email/**`
- Migration files: `database/migrations/**`.
- Main project config and tooling:
  - `artisan`
  - `composer.json`
  - `package.json`
  - `vite.config.js`
  - `tailwind.config.js`
- Project effort estimate document: `docs/project-effort-estimate.md`.
- Module-level `AGENTS.md` files do not exist at the moment; create one when a touched module gains non-trivial custom conventions.

## Architecture Rules
- Default backend/domain/API implementation and analysis scope is `app/V1/**`.
- Treat code outside `app/V1/**` as secondary/legacy unless the task explicitly requires it.
- Keep business rules, exceptions, DTOs, queries/commands, and repositories inside the owning module namespace.
- Do not move module-specific logic into `Core` unless it is truly shared by multiple modules.
- Prefer module-owned config files over `config/constants.php`; do not add new entries to `config/constants.php`.
- Keep API, domain, and persistence logic independent from Blade-only presentation concerns.
- Public API responses should be stable and explicit enough for external React consumers.

## Laravel And Module Conventions
- Use command-based flow for write operations: controllers dispatch commands and handlers own side effects.
- Validate request input in dedicated request classes.
- Domain validation and not-found checks should live in aggregates/services and use project/module-specific exceptions.
- New runtime text must be added through translations instead of inline literals.
- For this repository, add new translation keys in the owning Polish translation scope in the same task; other languages may remain unchanged unless explicitly requested.
- Keep controller context prefixes explicit: `Api`, `Front`, or `Admin`.
- Keep module names and namespaces aligned with existing `app/V1/Modules/**` structure.
- Keep module routes inside module-owned route service providers registered by the owning module service provider; do not introduce root `routes/**` files for module routes.

## Migration Rules
- New migrations must be created with `php artisan make:migration <migration-name>`.
- New migrations must include `declare(strict_types=1);`.
- New table migrations must extend `App\V1\Shared\Migrations\AbstractCreateTableMigration`, declare `protected string $table_name`, and implement only `up()`.
- Existing table modification migrations must extend `App\V1\Shared\Migrations\AbstractMigration`, declare `protected string $table_name`, and implement both `up()` and `down()` explicitly.
- Existing framework/vendor baseline migrations may still extend `Illuminate\Database\Migrations\Migration`; do not refactor them unless the task explicitly concerns migration normalization.
- Whenever a task adds at least one new migration file in `database/migrations`, run `php artisan migrate` in the same task as mandatory verification.

## Frontend Scope Rules
- Blade/Vite/Tailwind changes in this repository should support SEO public pages, server-rendered views, shared backend-adjacent assets, or temporary previews explicitly requested by the user.
- Do not build the future client dashboard or admin SPA here; those belong to the separate React project.
- If a backend change needs future React integration, document the API contract or response shape close to the implementation or in task documentation.

## Verification Checklist
- For backend/domain changes, run focused tests with `php artisan test --filter=...` when a focused target exists; otherwise run `php artisan test` when feasible.
- For migrations, run `php artisan migrate` after adding migration files.
- For frontend asset changes, run `npm install` only when dependencies changed, then run `npm run prod`.
- Run `composer phpstan` for PHP static analysis whenever PHP code, config, database files, or tests change.
- Do not use PHPStan baselines, ignore-error entries, or inline PHPStan suppressions as the default way to make analysis pass; fix the underlying typed contract or runtime assumption instead.
- For PHP syntax-sensitive isolated edits, run `php -l <file>` when faster than the full test suite and still meaningful.
- If verification cannot be run because of a missing dependency, unavailable service, or environment limitation, report the blocker and exact remediation.

## Documentation Workflow
- Keep `AGENTS.md`, `AGENTS_CODING_RULES.md`, `AGENTS_PROJECT_RULES.md`, and module-level `AGENTS.md` files synchronized with architecture and workflow decisions.
- If the user says `zapamietaj to na przyszlosc`, treat it as a mandatory documentation update in the relevant AGENTS document.
- If the user says `dopisz/zmien w AGENTS.md`, update the relevant AGENTS docs in scope, not only the root file when the rule belongs elsewhere.
- If a touched module gains non-trivial custom conventions and has no local `AGENTS.md`, create one in that module and register it in this file.
- If verified documentation drift is detected during any task, fix it in the same task.

## Effort Estimate Workflow
- Maintain cumulative project effort estimation in `docs/project-effort-estimate.md` using only module scope: `app/V1/Modules/**`.
- Exclude `app/V1/Core/**`, `app/V1/Shared/**`, shared frontend/assets areas, and AGENTS-only documentation changes from module effort calculations.
- At each commit action, include the current cumulative recommended project effort time from `docs/project-effort-estimate.md` in the commit communication/status update when the task touches module scope.

## Rule Ownership Separation
- Agent workflow, collaboration, git, verification reuse, and response format rules: `AGENTS.md`.
- Portable coding/style/implementation rules: `AGENTS_CODING_RULES.md`.
- Repository-specific Laravel paths, modules, product split, verification commands, and project contracts: `AGENTS_PROJECT_RULES.md`.
- Shared implementation contracts: local AGENTS files under `app/V1/Core/**` or `app/V1/Shared/**` when introduced.
- Module-specific functionality/contracts: module-level `AGENTS.md` files when introduced.
