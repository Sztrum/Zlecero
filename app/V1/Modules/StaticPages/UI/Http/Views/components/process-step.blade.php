@props([
    'step',
])

<article class="static-pages-process-step static-pages-process-step--{{ $step['variant'] }}">
    <span class="static-pages-process-step__connector" aria-hidden="true"></span>
    <span class="static-pages-process-step__icon" aria-hidden="true">
        {{ $step['icon'] }}
    </span>
    <p class="static-pages-process-step__eyebrow">
        {{ __('static-pages::home.process.step_label', ['number' => $step['number']]) }}
    </p>
    <h3>{{ $step['label'] }}</h3>
</article>
