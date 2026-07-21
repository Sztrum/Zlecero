@props([
    'name',
])

<svg
    {{ $attributes->merge([
        'class' => 'static-pages-icon',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-linecap' => 'round',
        'stroke-linejoin' => 'round',
        'stroke-width' => '2',
        'viewBox' => '0 0 24 24',
        'aria-hidden' => 'true',
    ]) }}

>
    @switch($name)
        @case('briefcase')
            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
            <rect width="20" height="14" x="2" y="6" rx="2" />
        @break

        @case('chevron-down')
            <path d="m6 9 6 6 6-6" />
        @break

        @case('check')
            <path d="M20 6 9 17l-5-5" />
        @break

        @case('check-circle')
            <path d="M9 12l2 2 4-4" />
            <circle cx="12" cy="12" r="10" />
        @break

        @case('circle')
            <circle cx="12" cy="12" r="10" />
        @break

        @case('file-question')
            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
            <path d="M14 2v6h6" />
            <path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 2-3 4" />
            <path d="M12 17h.01" />
        @break

        @case('file-text')
            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
            <path d="M14 2v6h6" />
            <path d="M16 13H8" />
            <path d="M16 17H8" />
            <path d="M10 9H8" />
        @break

        @case('layers')
            <path d="m12.83 2.18 8 4a1 1 0 0 1 0 1.79l-8 4a1.85 1.85 0 0 1-1.66 0l-8-4a1 1 0 0 1 0-1.79l8-4a1.85 1.85 0 0 1 1.66 0z" />
            <path d="m22 12-9.17 4.59a1.85 1.85 0 0 1-1.66 0L2 12" />
            <path d="m22 17-9.17 4.59a1.85 1.85 0 0 1-1.66 0L2 17" />
        @break

        @case('mail')
            <rect width="20" height="16" x="2" y="4" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
        @break

        @case('x')
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        @break

        @case('x-circle')
            <circle cx="12" cy="12" r="10" />
            <path d="m15 9-6 6" />
            <path d="m9 9 6 6" />
        @break
    @endswitch
</svg>
