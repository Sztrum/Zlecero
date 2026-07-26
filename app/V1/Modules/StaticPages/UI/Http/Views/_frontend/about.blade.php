@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zl-page">
        @include('static_pages::_frontend.partials.nav')
        <section class="zl-subhero">
            <div class="zl-subhero__inner">
                <h1>{{ $content['title'] }}</h1>
                <p>{{ $content['lead'] }}</p>
            </div>
        </section>
        <section class="zl-subpage-section">
            <div class="zl-story-grid">
                @foreach($content['blocks'] as $block)
                    <article class="zl-story-card">
                        <h2>{{ $block['title'] }}</h2>
                        <p>{{ $block['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>
        <section class="zl-cta zl-cta--subpage is-revealed">
            <div class="zl-cta__inner">
                <div class="zl-cta__content">
                <h2>{{ $content['title'] }}</h2>
                <p>{{ $content['lead'] }}</p>
                <div class="zl-cta__actions">
                    <a class="zl-button zl-button--light" href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a>
                </div>
                </div>
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
