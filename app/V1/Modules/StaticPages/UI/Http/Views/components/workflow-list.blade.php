@props([
    'items',
    'title',
    'variant',
])

<article class="static-pages-workflow-list static-pages-workflow-list--{{ $variant }}">
    <h3>
        <span aria-hidden="true">
            <x-static-pages::icon :name="$variant === 'current' ? 'x-circle' : 'check-circle'" />
        </span>
        {{ $title }}
    </h3>
    <div class="static-pages-workflow-list__items">
        @foreach($items as $item)
            <p>
                <span aria-hidden="true">
                    <x-static-pages::icon :name="$variant === 'current' ? 'x' : 'check'" />
                </span>
                {{ $item }}
            </p>
        @endforeach
    </div>
</article>
