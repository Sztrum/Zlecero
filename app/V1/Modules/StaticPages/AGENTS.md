# AGENTS.md

## Version
v1.5.0

## Scope
Module-specific rules for `app/V1/Modules/StaticPages/**`.

## Purpose
This file defines conventions for SEO/public static pages served from the Laravel Zlecero project.

## Static Pages Styling
- Preserve the current Zlecero reference visual direction unless the user explicitly asks for a redesign: warm cream background `#FAF5ED`, dark brown hero/footer `#33251D`, brick primary `#9C442D`, white product surfaces, 8px default radius, restrained brown-tinted shadows, Inter body typography, Plus Jakarta Sans display typography, and compact dashboard-like product preview patterns.
- When the user provides a Figma/exported reference for a public static page, treat that reference as the visual source of truth for typography, palette, spacing, radius, shadows, iconography, and component proportions; map those values into the repository Blade components and core SCSS instead of approximating them with the existing project Tailwind palette.
- Convert React landing page UI patterns into Blade components plus core SCSS partials that use Tailwind `@apply`; do not copy long Tailwind utility strings directly into Blade templates when the pattern can be named and reused.
- Keep repeatable public-page UI pieces such as buttons, logos, section headings, cards, process steps, workflow lists, FAQ items, and forms as module Blade components plus core SCSS partials.
- Keep StaticPages SCSS in `app/V1/Core/UI/Http/Resources/scss/**`: reusable patterns belong in `components/**`, while the landing composition belongs in `pages/**`.

## Static Pages Localization
- Public static pages must expose the active language in the URL path, such as `/pl`, `/en`, and `/de`; use a cookie only to remember the preferred language for redirects from non-localized entry points like `/`.
- Public static page language switchers must render direct localized links instead of JavaScript-only language changes.
- Keep public-page runtime text in module translations and prepare repeatable data structures in controllers or view composers instead of hardcoding arrays in Blade.

## Static Pages React App Links
- Public Blade links that navigate to React-owned app routes such as login, registration, dashboard, onboarding, or password screens must be generated from the configured frontend endpoint service and must not use raw relative hrefs like `/login` unless Laravel also owns that GET route.
