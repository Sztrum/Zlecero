@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('custom_js_head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($landingReference['faqs'])->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
            ])->values()->all(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('main-content')
    <div class="zl-page">
        @include('static_pages::_frontend.partials.nav')
        <section class="zl-faq-page-head">
            <div class="zl-faq-page-head__inner">
                <h1>Poznaj najczęstsze pytania i odpowiedzi dotyczące pracy z Zlecero.</h1>
                <p>Masz inne pytanie? <a href="{{ url('/'.$locale.'/contact') }}">Skontaktuj się z nami.</a></p>
            </div>
        </section>
        <section class="zl-faq-page-list">
            <div class="zl-faq-page-list__inner">
                @foreach($landingReference['faqs'] as $faq)
                    <article class="zl-faq-card">
                        <button class="zl-faq-card__button" type="button" aria-expanded="false">
                            <span>{{ $faq['q'] }}</span>
                            <span>⌄</span>
                        </button>
                        <div class="zl-faq-card__answer">
                            <div>{{ $faq['a'] }}</div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
