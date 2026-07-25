@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zp-page">
        @include('static_pages::_frontend.partials.nav')
        <section class="zp-subhero">
            <h1>{{ $content['title'] }}</h1>
            <p>{{ $content['lead'] }}</p>
        </section>
        <section class="zp-section">
            <div class="zp-pricing">
                @foreach($content['plans'] as $plan)
                    <article>
                        <h2>{{ $plan['name'] }}</h2>
                        <strong>{{ $plan['price'] }}</strong>
                        <p>{{ $plan['caption'] }}</p>
                        @foreach($plan['features'] as $feature)
                            <span>✓ {{ $feature }}</span>
                        @endforeach
                        <a class="zp-button zp-button--primary" href="/auth/register">{{ $shared['trial_cta'] }}</a>
                    </article>
                @endforeach
            </div>
        </section>
        <section class="zp-section zp-section--muted">
            <div class="zp-faq-list">
                @foreach($content['billing_faq'] as $item)
                    <article>
                        <h2>{{ $item['question'] }}</h2>
                        <p>{{ $item['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
