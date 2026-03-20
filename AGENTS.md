# AGENTS.md

## Version
v1.17.2

## Scope
This document applies to the entire repository.

## Purpose
This file is the root index for repository-specific agent rules.
Cross-project portable workflow/style rules are kept in:
- `AGENTS_GLOBAL_WORKFLOW_RULES.md`
- `AGENTS_GLOBAL_RULES.md`

## Required document read order
Read documents in this exact order before implementation:
1. `AGENTS_GLOBAL_WORKFLOW_RULES.md`
2. `AGENTS_GLOBAL_RULES.md`
3. repository root `AGENTS.md`
4. any local/module `AGENTS.md` files in touched scope (if present)

## Documentation map (current project)
- Portable/global workflow rules (cross-project): `AGENTS_GLOBAL_WORKFLOW_RULES.md`
- Portable/global coding rules (cross-project): `AGENTS_GLOBAL_RULES.md`
- Root repository rules (this file): `AGENTS.md`
- Project-level cumulative effort log (modules-only scope): `docs/project-effort-estimate.md`
- Main implementation scope: `app/V1/**`
- Shared architecture layers:
  - `app/V1/Core/**`
  - `app/V1/Shared/**`
- Main project entry/config files:
  - `artisan`
  - `composer.json`
  - `package.json`
- Current AGENTS coverage:
  - repository root rules: `AGENTS.md`
  - no module-local `AGENTS.md` files exist at the moment
- Modules:
  - `app/V1/Modules/Auth/**`
  - `app/V1/Modules/User/**`
  - `app/V1/Modules/Country/**`
  - `app/V1/Modules/Email/**`

## Project-specific cross-cutting rules
- Default implementation and analysis scope is `app/V1/**`.
- Treat code outside `app/V1/**` as secondary/legacy unless the task explicitly requires it.
- Keep business rules, exceptions, DTOs, queries/commands, and repositories inside the owning module namespace.
- Do not move module-specific logic into `Core` unless it is truly shared by multiple modules.
- Do not add new entries to `config/constants.php`; prefer module/core config files.
- Every new runtime text should be added through translations instead of inline literals; for this repository, add the key in the owning Polish translation scope in the same task, while other languages may stay unchanged unless explicitly requested.
- Whenever a task adds at least one new migration file in `database/migrations`, run `php artisan migrate` in the same task as mandatory verification.
- For backend/domain changes, run at least focused tests (`php artisan test --filter=...`) or explain why tests were not run.
- For frontend asset changes (Vite/Tailwind/JS/SCSS), run `npm install` (if deps changed) and `npm run prod` at repository root.
- Maintain cumulative project effort estimation in `docs/project-effort-estimate.md` using only module scope (`app/V1/Modules/**`); explicitly exclude `app/V1/Core/**`, `app/V1/Shared/**`, and shared frontend/assets areas from time calculations.
- At each commit action, include the current cumulative recommended project effort time from `docs/project-effort-estimate.md` in the commit communication/status update.

## Documentation workflow (mandatory)
1. Before coding, read root `AGENTS.md` and all relevant local/module `AGENTS.md` files in touched scope (if present).
2. If behavior/architecture decisions change during implementation, update the relevant `AGENTS.md` in the same task.
3. If a user says "zapamietaj to na przyszlosc", treat it as mandatory documentation update in relevant AGENTS docs.
4. If a user says "dopisz/zmien w AGENTS.md", update all relevant AGENTS docs in scope (root and/or module).
5. If a touched module gains non-trivial custom conventions and has no local `AGENTS.md`, create one in that module and register it in this file.
6. If you detect verified documentation drift during any task, fix it in the same task.
7. Keep all relevant `AGENTS*.md` documents updated continuously during implementation; do not postpone AGENTS updates to the end of the task.

## Rule ownership separation
- Cross-project workflow/response rules -> `AGENTS_GLOBAL_WORKFLOW_RULES.md`
- Cross-project coding/style rules -> `AGENTS_GLOBAL_RULES.md`
- Repository-specific cross-cutting rules -> root `AGENTS.md`
- Shared implementation contracts -> `app/V1/Core/**` docs (local AGENTS when introduced)
- Module-specific functionality/contracts -> module-local `AGENTS.md` (when present)
