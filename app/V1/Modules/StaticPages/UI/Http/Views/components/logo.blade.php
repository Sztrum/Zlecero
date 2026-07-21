@props([
    'size' => 'md',
])

<a
    {{ $attributes->merge([
        'class' => 'static-pages-logo static-pages-logo--' . $size,
        'href' => '#top',
    ]) }}
>
    <span class="static-pages-logo__mark" aria-hidden="true">
        <span></span>
    </span>
    <span class="static-pages-logo__text">Zlecero</span>
</a>
