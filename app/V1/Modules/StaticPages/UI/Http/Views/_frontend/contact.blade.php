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
            <div class="zp-contact">
                <aside>
                    <h2>{{ $shared['brand'] }}</h2>
                    <p>{{ $shared['footer']['description'] }}</p>
                    <a href="mailto:kontakt@zlecero.pl">kontakt@zlecero.pl</a>
                </aside>
                <form method="POST" action="{{ route('front.static_pages.contact.submit', ['locale' => $locale]) }}" novalidate>
                    @csrf
                    @if(session('contact_status'))
                        <div class="zp-alert">{{ session('contact_status') }}</div>
                    @endif
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="zp-honeypot" aria-hidden="true">
                    <label>{{ $content['form']['name'] }}<input name="name" value="{{ old('name') }}" required></label>
                    <label>{{ $content['form']['company'] }}<input name="company" value="{{ old('company') }}" required></label>
                    <label>{{ $content['form']['email'] }}<input type="email" name="email" value="{{ old('email') }}" required></label>
                    <label>{{ $content['form']['phone'] }}<input name="phone" value="{{ old('phone') }}"></label>
                    <label>{{ $content['form']['subject'] }}<input name="subject" value="{{ old('subject') }}" required></label>
                    <label>{{ $content['form']['message'] }}<textarea name="message" rows="5" required>{{ old('message') }}</textarea></label>
                    @if($errors->any())
                        <div class="zp-alert zp-alert--error">{{ $errors->first() }}</div>
                    @endif
                    <button class="zp-button zp-button--primary zp-button--large" type="submit">{{ $content['form']['submit'] }}</button>
                </form>
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
