@props([
    'item',
])

<article class="static-pages-problem-card">
    <span class="static-pages-problem-card__icon" aria-hidden="true">
        {{ $item['icon'] }}
    </span>
    <h3>{{ $item['title'] }}</h3>
    <p>{{ $item['description'] }}</p>
</article>
