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
            <div class="zp-story-grid">
                @foreach($content['blocks'] as $block)
                    <article>
                        <h2>{{ $block['title'] }}</h2>
                        <p>{{ $block['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>
        <section class="zp-pilot">
            <div>
                <h2>{{ $content['title'] }}</h2>
                <p>{{ $content['lead'] }}</p>
                <a class="zp-button zp-button--light zp-button--large" href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a>
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
