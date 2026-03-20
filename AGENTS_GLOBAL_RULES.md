# AGENTS_GLOBAL_RULES.md

## Version
v3.1.0

## Scope
Portable coding/style/implementation standards intended to be reusable across projects.

## Purpose
This file contains flexible global coding/style/implementation rules and should remain framework/project-agnostic whenever possible.
AI-agent execution/workflow/response rules are defined in `AGENTS_GLOBAL_WORKFLOW_RULES.md`.
Project-specific paths, providers, and module contracts should stay in repository `AGENTS.md` files.

## Required document read order
Read documents in this exact order before implementation:
1. `AGENTS_GLOBAL_WORKFLOW_RULES.md`
2. `AGENTS_GLOBAL_RULES.md`
3. repository root `AGENTS.md`
4. all relevant module/local `AGENTS.md` files in scope

## Architecture and routing
- Prefer small, well-scoped changes with clear commits.
- When changes span backend and frontend behavior, keep both sides aligned in the same task.
- Keep extension-point abstractions/bases separated from concrete implementations: directories meant for reusable base classes (for example route/config bases to extend in modules) must not contain project-specific concrete implementations; place concrete core implementations in a dedicated sibling area.
- Default API routing to plural prefixes and route names; disable pluralization only when plural forms are awkward.
- Keep simple route chains on one line (for example `->name()`), and use explicit parameter suffixes like `_id` or `_slug`.

## Config and repositories
- Keep module/domain config in module-owned config folders and access via module-scoped config keys.
- Prefer dedicated config repositories over repeated ad-hoc config lookups.
- Keep config keys consistent and explicit (for example hyphen-based key style).
- Store config values in native types (booleans/integers/arrays), not stringified primitives.
- For config reads, do not cast values after config access (for example `(string)`, `(array)`, `(bool)`); validate expected types explicitly and throw on mismatches so invalid config structures fail fast.
- For optional config entry shapes, declare all supported keys and set unused keys to `null`.
- Keep config files logically scoped: settings/options files should contain only settings/options; large registries/lists (for example all languages, form registries, item catalogs) should be split into dedicated config files and read via dedicated repositories.
- Keep repository naming explicit and non-redundant.
- In base classes, place overridable extension hooks at the end of the class.

## Controllers, handlers, domain flow
- Prefer `Illuminate\Support\Collection` over raw PHP arrays for list/iterable flows whenever possible; keep plain arrays only where framework contracts require arrays or where keyed config payloads must remain array-structured.
- For write operations, use command-based flow where controllers dispatch commands and handlers own side effects.
- Avoid direct model querying/mutation in controllers/handlers when repositories are available.
- Route domain validation (including not-found checks) through domain aggregates/services rather than ad-hoc controller logic.
- If aggregate/service calls can throw, document it with explicit `@throws` annotations.
- Avoid silent early returns for required business data; fail explicitly with domain-level validation/exceptions.
- Keep tightly coupled operations adjacent (for example fetch + immediate validation without unrelated spacing).
- Validate request input in dedicated request classes, not inline controller validation.
- Controller names should use explicit context prefixes (`Api`, `Front`, `Admin`) and `Controller` suffixes.
- Read route parameters from request route context in controller methods instead of type-hinting route primitives in signatures.

## Dependency injection and typing
- Prefer constructor dependency injection over service-locator/facade/helper access where practical.
- Inject required dependencies directly; do not hide mandatory dependencies behind constructor `make()` fallbacks.
- Keep constructor dependency order from most general to most domain-specific.
- Keep identifier typing consistent with entity strategy (for example UUID-first modules use string IDs).
- Avoid unnecessary casting; cast only when required by type or input format.
- Use DTO libraries only when they provide tangible value (normalization/casting/enum mapping/derived fields/reuse).
- Use explicit naming for collection variables (for example `$allXxx`) and DTO variables (`...DTO` suffix).
- When return types are broad (`mixed`/generic), add explicit `/** @var ... */` annotations at assignment sites when callers expect specific types.

## Data contracts and schema safety
- For new modules/features/entities, use UUID identifiers by default; do not introduce new integer PK/FK patterns unless integrating with an existing legacy integer-based table.
- For payload contracts, implement exactly one shape defined by the user/example; do not add implicit support for alternative payload formats/types.
- In migrations, every FK column type must match the referenced PK column type 1:1 (including signed/unsigned and size) to avoid MySQL incompatibility errors.

## Blade and frontend implementation
- Start Blade components with `@props([])` or `@props([...])` and keep default values explicit.
- Keep components data-driven; avoid hardcoded content when props/config should drive rendering.
- Reuse existing components for repeatable UI patterns; extract shared components instead of duplicating markup.
- Use camelCase prop bindings when invoking Blade components.
- Prepare data in controllers/view composers; avoid loading service/config logic directly inside Blade templates.
- Avoid single-use Blade temporary variables when inline expressions stay readable.
- For fixed required config structures, use direct key access so missing keys fail loudly.
- Keep section IDs explicit and convention-based (for example `*-section`) and avoid storing structural IDs in config.
- For multi-attribute tags, keep one attribute per line and readable class/merge formatting.
- Any interactive click/tap element should expose pointer cursor styling.
- Keep page/section assets split by responsibility; avoid coupling section behavior into unrelated page scripts.
- Use explicit and unambiguous TS selectors (prefer dedicated class/id hooks over broad tag selectors).

## PHP style and maintainability
- Prefer multi-line signatures for parameterized methods (except compact handlers like `handle(CommandInterface $command): void`).
- Separate logical blocks in arrays/method bodies with blank lines for readability.
- Avoid single-use temporary variables when inline expressions remain readable.
- In DDD/module code, prefer the simplest readable solution that preserves clear responsibility boundaries; keep each class/method focused on one primary responsibility and avoid mixing orchestration, data seeding, and unrelated domain concerns in the same unit.
- When removing behavior/config, remove stale related artifacts as part of the same change.
- Prefer project-specific custom exceptions over framework/default generic exceptions (for example `InvalidArgumentException`) whenever a domain/system-specific failure can be expressed and translated.
- Prefer `throw_if(...)` over `if (...) { throw ...; }` for guard clauses throwing exceptions.
- In functions/methods where exceptions are thrown (including but not limited to `throw_if(...)`), add explicit `@throws <SpecificException>|Throwable` PHPDoc annotations when missing.

## Module and class naming
- Keep module namespaces/names singular where architecture allows; keep class suffixes explicit (`*ServiceProvider`, `*Repository`, `*Controller`, etc.).
- Align module names with primary domain model names where practical.
- Keep component class naming aligned with component path semantics.
- Naming must be explicit and responsibility-driven across all artifacts (files, classes, configs, controllers, services, providers, DTOs, etc.): names should communicate real content/behavior immediately, and generic/ambiguous names should be avoided.
- Retrieval method naming must be explicit: use `get...` for returning data sets/collections/arrays and `first...` for returning a single first item or `null`; avoid ambiguous names like `items()`, `data()`, `list()`, `all...()` without a retrieval verb.
- For classes that extend another project class, the child class suffix must include the full suffix/class-name pattern of the extended base (for example extending `LocalizedConfigRepository` requires `...LocalizedConfigRepository` in the child class name).

## Documentation and upkeep
- When a user asks to change a project-wide standard/pattern, update all usages across the codebase (including constructor DI and base classes) so the standard is applied consistently end-to-end.
- Every `AGENTS*.md` file must contain an explicit version marker (`## Version`) at the top or bottom of the file.
- Versioning format for AGENTS docs in this workflow is Semantic Versioning: `v<major>.<minor>.<patch>` (for example `v1.17.0`, `v1.17.1`).
- On every change to a given `AGENTS*.md` file, increment that file version.
- Before editing any `AGENTS*.md` file, always create a versioned backup of the pre-change file in `docs/agents-backups/` using the previous version in filename (for example `docs/agents-backups/AGENTS_GLOBAL_RULES_v1.17.0.md` before updating to `v1.17.1`).
- For routine/small updates, increment `PATCH` (`v1.17.0` -> `v1.17.1`).
- For backward-compatible rule additions/expansions, increment `MINOR` and reset patch (`v1.17.3` -> `v1.18.0`).
- For major/structural rule-set changes or incompatible policy shifts, increment `MAJOR` and reset minor/patch (`v1.18.4` -> `v2.0.0`).
- Each `AGENTS*.md` file is versioned independently.
- Keep docs synchronized with behavior changes in the same task.
- At start/end of work iterations, quickly verify docs still match current behavior.
- Keep selectors and usages synchronized across templates/scripts/styles.
- Keep Markdown documentation in English unless repository policy states otherwise.
- For globally shared view values, use a consistent key prefix policy.
