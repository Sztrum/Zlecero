@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zp-page">
        @include('static_pages::_frontend.partials.nav')

        <section class="zp-hero">
            <div class="zp-hero__grid">
                <div>
                    <span class="zp-pill"><i></i>{{ $shared['pilot_badge'] }}</span>
                    <h1>{{ $content['hero_title'] }}</h1>
                    <p>{{ $content['hero_text'] }}</p>
                    <div class="zp-hero__actions">
                        <a class="zp-button zp-button--primary zp-button--large" href="/auth/register">{{ $shared['trial_cta'] }}</a>
                        <a class="zp-button zp-button--secondary zp-button--large" href="#jak-dziala">{{ $shared['demo_cta'] }}</a>
                    </div>
                </div>
                @include('static_pages::_frontend.partials.product-preview')
            </div>
        </section>

        <section id="dla-kogo" class="zp-section zp-section--muted">
            <div class="zp-section__head">
                <h2>{{ $content['problem_title'] }}</h2>
                <p>{{ $content['problem_text'] }}</p>
            </div>
            <div class="zp-card-grid">
                @foreach($content['problems'] as $problem)
                    <article class="zp-problem-card">
                        <span>{{ strtoupper(substr($problem['icon'], 0, 2)) }}</span>
                        <h3>{{ $problem['title'] }}</h3>
                        <p>{{ $problem['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="jak-dziala" class="zp-section">
            <div class="zp-section__head">
                <h2>{{ $content['how_title'] }}</h2>
                <p>{{ $content['how_text'] }}</p>
            </div>
            <div class="zp-steps">
                @foreach($content['steps'] as $index => $step)
                    <article>
                        <span>{{ $index + 1 }}</span>
                        <small>KROK {{ $index + 1 }}</small>
                        <strong>{{ $step['label'] }}</strong>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="funkcje" class="zp-section zp-section--muted">
            <div class="zp-section__head">
                <h2>{{ $content['comparison_title'] }}</h2>
            </div>
            <div class="zp-compare">
                <article>
                    <h3>Dzisiaj</h3>
                    @foreach($content['today'] as $item)
                        <p><span>×</span>{{ $item }}</p>
                    @endforeach
                </article>
                <article class="zp-compare__positive">
                    <h3>Zlecero</h3>
                    @foreach($content['zlecero'] as $item)
                        <p><span>✓</span>{{ $item }}</p>
                    @endforeach
                </article>
            </div>
        </section>

        <section id="cennik" class="zp-pilot">
            <div>
                <span>{{ $shared['pilot_badge'] }}</span>
                <h2>{{ $content['pilot_title'] }}</h2>
                <p>{{ $content['pilot_text'] }}</p>
                <a class="zp-button zp-button--light zp-button--large" href="/auth/register">{{ $shared['trial_cta'] }}</a>
            </div>
        </section>

        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
