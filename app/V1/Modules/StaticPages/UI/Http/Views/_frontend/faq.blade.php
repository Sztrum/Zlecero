@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('custom_js_head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($content['sections'])->flatMap(fn ($section) => $section['items'])->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
            ])->values()->all(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('main-content')
    <div class="zp-page">
        @include('static_pages::_frontend.partials.nav')
        <section class="zp-subhero">
            <h1>{{ $content['title'] }}</h1>
            <p>{{ $content['lead'] }}</p>
        </section>
        <section class="zp-section">
            <div class="zp-faq-list">
                @foreach($content['sections'] as $section)
                    <div class="zp-faq-section">
                        <h2>{{ $section['title'] }}</h2>
                        @foreach($section['items'] as $item)
                            <article id="{{ $item['slug'] }}">
                                <h3><a href="#{{ $item['slug'] }}">{{ $item['question'] }}</a></h3>
                                <p>{{ $item['answer'] }}</p>
                            </article>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
