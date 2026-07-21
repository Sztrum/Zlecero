@props([
    'faq',
])

<article
    class="static-pages-faq-item"
    x-data="{ open: false }"
>
    <button
        type="button"
        x-bind:aria-expanded="open.toString()"
        x-on:click="open = !open"
    >
        <span>{{ $faq['question'] }}</span>
        <span
            class="static-pages-faq-item__chevron"
            x-bind:class="{ 'static-pages-faq-item__chevron--open': open }"
            aria-hidden="true"
        >
            <x-static-pages::icon name="chevron-down" />
        </span>
    </button>
    <div
        class="static-pages-faq-item__answer"
        x-show="open"
        x-cloak
    >
        {{ $faq['answer'] }}
    </div>
</article>
