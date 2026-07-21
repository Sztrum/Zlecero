@props([
    'items',
    'title',
    'variant',
])

<article class="static-pages-workflow-list static-pages-workflow-list--{{ $variant }}">
    <h3>
        <span aria-hidden="true">{{ $variant === 'current' ? 'x' : 'ok' }}</span>
        {{ $title }}
    </h3>
    <div class="static-pages-workflow-list__items">
        @foreach($items as $item)
            <p>
                <span aria-hidden="true">{{ $variant === 'current' ? 'x' : 'ok' }}</span>
                {{ $item }}
            </p>
        @endforeach
    </div>
</article>
