# AGENTS_CODING_RULES.md

## Version
v1.0.0

## Scope
Portable coding/style/implementation standards intended to be reusable across projects.

## Purpose
This file contains flexible global coding/style/implementation rules and should remain framework/project-agnostic whenever possible. AI-agent execution/workflow/response rules are defined in `AGENTS.md`. Project-specific paths, providers, and module contracts must stay in `AGENTS_PROJECT_RULES.md`.

## Required Document Read Order
Read documents in this exact order before implementation:
1. `AGENTS.md`
2. `AGENTS_CODING_RULES.md`
3. `AGENTS_PROJECT_RULES.md`
4. all relevant module-level `AGENTS.md` files in scope

## Architecture And Routing
- Prefer small, well-scoped changes with clear commits.
- When changes span backend and frontend behavior, keep both sides aligned in the same task.
- Keep extension-point abstractions/bases separated from concrete implementations.
- Default API routing to plural prefixes and route names; disable pluralization only when plural forms are awkward.
- Keep simple route chains on one line and use explicit parameter suffixes such as `_id` or `_slug`.
- When migrating behavior from a legacy source to a new canonical source, do not preserve compatibility fallbacks to the legacy path unless the user explicitly requests transitional support.

## Config And Repositories
- Keep module/domain config in module-owned config folders and access via module-scoped config keys.
- Prefer dedicated config repositories over repeated ad-hoc config lookups.
- Keep config keys consistent and explicit.
- Store config values in native types, not stringified primitives.
- For config reads, do not cast values after config access; validate expected types explicitly and throw on mismatches.
- For optional config entry shapes, declare all supported keys and set unused keys to `null`.
- Add concise English comments in config files only for keys whose purpose is not obvious from the key name/value alone.
- Keep config files logically scoped; split large registries/lists into dedicated config files and read them via dedicated repositories.
- Keep repository naming explicit and non-redundant.
- Config repository classes must follow the `{ModuleName}{ConfigScope}ConfigRepository` pattern.
- Every concrete `*ConfigRepository` class must include a class-level PHPDoc `@see` pointing to the source config file path.
- In base classes, place overridable extension hooks at the end of the class.

## Controllers, Handlers, Domain Flow
- Prefer `Illuminate\Support\Collection` over raw PHP arrays for list/iterable flows whenever possible.
- For write operations, use command-based flow where controllers dispatch commands and handlers own side effects.
- Avoid direct model querying/mutation in controllers/handlers when repositories are available.
- Route domain validation through domain aggregates/services rather than ad-hoc controller logic.
- If aggregate/service calls can throw, document it with explicit `@throws` annotations.
- Avoid silent early returns for required business data; fail explicitly with domain-level validation/exceptions.
- Keep tightly coupled operations adjacent.
- Validate request input in dedicated request classes, not inline controller validation.
- Controller names should use explicit context prefixes such as `Api`, `Front`, or `Admin`, and must end with `Controller`.
- Read route parameters from request route context in controller methods instead of type-hinting route primitives in signatures.

## Dependency Injection And Typing
- Prefer constructor dependency injection over service-locator/facade/helper access where practical.
- When constructor DI is unavailable, resolve concrete classes from the container instead of using facades or helpers.
- Do not hide mandatory dependencies behind constructor `make()` fallbacks.
- Keep constructor dependency order from most general to most domain-specific.
- Keep identifier typing consistent with entity strategy.
- Avoid unnecessary casting; cast only when required by type or input format.
- Use DTO libraries only when they provide tangible value.
- Use explicit naming for collection variables and DTO variables.
- When assigning Eloquent model instances to variables, include the model name in the variable and add a `/** @var ModelName $var */` annotation when the repository return type is generic.
- When resolving dependencies from the container, assign the instance to a matching variable name and add a `/** @var ClassName $var */` annotation for IDE typing.
- When return types are broad, add explicit `/** @var ... */` annotations at assignment sites when callers expect specific types.

## Data Contracts And Schema Safety
- For new modules/features/entities, use UUID identifiers by default; do not introduce integer PK/FK patterns unless integrating with an existing legacy integer-based table.
- For payload contracts, implement exactly one shape defined by the user/example.
- In migrations, every FK column type must match the referenced PK column type 1:1.

## Migrations
- Always create migration files using `php artisan make:migration <migration-name>`; never create migration files manually by hand.
- All new project migrations must extend one of the project's shared abstract migration base classes documented in `AGENTS_PROJECT_RULES.md`.
- For migrations that create a new table, extend the project create-table base migration, declare the table-name property expected by that base class, and implement only `up()`.
- For migrations that modify an existing table, extend the project general base migration, declare the table-name property expected by that base class, and implement both `up()` and `down()` explicitly.
- Always include `declare(strict_types=1);` at the top of every migration file.

## Blade And Frontend Implementation
- Start Blade components with `@props([])` or `@props([...])` and keep default values explicit.
- Keep components data-driven; avoid hardcoded content when props/config should drive rendering.
- Reuse existing components for repeatable UI patterns; extract shared components instead of duplicating markup.
- Use camelCase prop bindings when invoking Blade components.
- Prepare data in controllers/view composers; avoid loading service/config logic directly inside Blade templates.
- Avoid PHP logic blocks in Blade templates; move view data preparation to controllers/view composers.
- In controller view payloads, separate shared/meta keys from page-specific keys with a blank line.
- Avoid single-use Blade temporary variables when inline expressions stay readable.
- For fixed required config structures, use direct key access so missing keys fail loudly.
- Keep section IDs explicit and convention-based.
- For multi-attribute tags, keep one attribute per line, keep `@class([...])` on a new line, and add a blank line after `{{ $attributes->merge(...) }}` in tags.
- Any interactive click/tap element should expose pointer cursor styling.
- Keep page/section assets split by responsibility.
- Use explicit and unambiguous TypeScript selectors.
- In JavaScript translation or validation message maps, prefer double-quoted strings for localized copy.

## User-Facing Output Formatting
- For user-facing numeric output, format values in a human-friendly way:
  - apply locale-appropriate thousands grouping separators;
  - use consistent decimal separators/precision suitable for the metric;
  - present byte/size metrics in readable units instead of raw byte totals.

## PHP Style And Maintainability
- Prefer multi-line signatures for parameterized methods except compact handlers like `handle(CommandInterface $command): void`.
- Separate logical blocks in arrays/method bodies with blank lines for readability.
- Avoid single-use temporary variables when inline expressions remain readable.
- When removing behavior/config, remove stale related artifacts in the same change.
- Prefer project-specific custom exceptions over framework/default generic exceptions whenever a domain/system-specific failure can be expressed and translated.
- Prefer `throw_if(...)` over `if (...) { throw ...; }` for guard clauses throwing exceptions.
- In functions/methods where exceptions are thrown, add explicit `@throws <SpecificException>|Throwable` PHPDoc annotations when missing.

## Module And Class Naming
- Keep module namespaces/names singular where architecture allows.
- Keep class suffixes explicit.
- Align module names with primary domain model names where practical.
- Module name constants/usages must match the module name defined in the module service provider.
- Keep component class naming aligned with component path semantics.
- Naming must be explicit and responsibility-driven across all artifacts.
- Retrieval method naming must be explicit: use `get...` for datasets and `first...` for a single first item or `null`.
- For classes that extend another project class, the child class suffix must include the full suffix/class-name pattern of the extended base.

## Documentation And Upkeep
- When a user asks to change a project-wide standard/pattern, update all usages across the codebase so the standard is applied consistently.
- Every `AGENTS*.md` file must contain an explicit version marker.
- Versioning format for AGENTS docs is Semantic Versioning.
- On every change to a given `AGENTS*.md` file, increment that file version.
- For routine/small updates, increment `PATCH`.
- For backward-compatible rule additions/expansions, increment `MINOR` and reset patch.
- For major/structural rule-set changes or incompatible policy shifts, increment `MAJOR` and reset minor/patch.
- Each `AGENTS*.md` file is versioned independently.
- Keep docs synchronized with behavior changes in the same task.
- At start/end of work iterations, quickly verify docs still match current behavior.
- Keep selectors and usages synchronized across templates/scripts/styles.
- Keep Markdown documentation in English unless repository policy states otherwise.
- For globally shared view values, use a consistent key prefix policy.
