@props([
    'href' => '#',
    'variant' => 'primary',
])

<a
    {{ $attributes->merge([
        'class' => 'static-pages-button static-pages-button--' . $variant,
        'href' => $href,
    ]) }}
>
    {{ $slot }}
</a>
