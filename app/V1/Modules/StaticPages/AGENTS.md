# AGENTS.md

## Version
v1.1.0

## Scope
Module-specific rules for `app/V1/Modules/StaticPages/**`.

## Purpose
This file defines conventions for SEO/public static pages served from the Laravel Zlecero project.

## Static Pages Styling
- Preserve the current Zlecero landing page visual direction unless the user explicitly asks for a redesign: clean white and muted-neutral surfaces, blue primary accents from the project Tailwind palette, compact rounded cards, restrained shadows, and dashboard-like product preview patterns.
- Convert React landing page UI patterns into Blade components plus core SCSS partials that use Tailwind `@apply`; do not copy long Tailwind utility strings directly into Blade templates when the pattern can be named and reused.
- Keep repeatable public-page UI pieces such as buttons, logos, section headings, cards, process steps, workflow lists, FAQ items, and forms as module Blade components plus core SCSS partials.
- Keep StaticPages SCSS in `app/V1/Core/UI/Http/Resources/scss/**`: reusable patterns belong in `components/**`, while the landing composition belongs in `pages/**`.
- Keep public-page runtime text in module translations and prepare repeatable data structures in controllers or view composers instead of hardcoding arrays in Blade.
