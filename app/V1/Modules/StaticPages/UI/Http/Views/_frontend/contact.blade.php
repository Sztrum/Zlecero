@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zl-page">
        @include('static_pages::_frontend.partials.nav')
        <section class="zl-contact-head">
            <div class="zl-contact-head__inner">
                <h1>Zobaczmy, jak Zlecero może uporządkować sprzedaż w Twojej firmie.</h1>
                <p>Odpowiadamy w ciągu jednego dnia roboczego.</p>
            </div>
        </section>
        <section class="zl-contact-page">
            <div class="zl-contact-page__grid">
                <form class="zl-contact__form" method="POST" action="{{ route('front.static_pages.contact.submit', ['locale' => $locale]) }}" novalidate>
                    @csrf
                    <h2>Opowiedz nam, czego potrzebujesz</h2>
                    @if(session('contact_status'))
                        <div class="zl-alert">{{ session('contact_status') }}</div>
                    @endif
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="zl-honeypot" aria-hidden="true">
                    <div class="zl-contact__form-grid">
                        <label>{{ $content['form']['name'] }}<input name="name" value="{{ old('name') }}" placeholder="Jan Kowalski" required></label>
                        <label>{{ $content['form']['company'] }}<input name="company" value="{{ old('company') }}" placeholder="Acme Sp. z o.o." required></label>
                        <label>{{ $content['form']['email'] }}<input type="email" name="email" value="{{ old('email') }}" placeholder="jan@firma.pl" required></label>
                        <label>{{ $content['form']['phone'] }}<input name="phone" value="{{ old('phone') }}" placeholder="+48 500 000 000"></label>
                    </div>
                    <label>{{ $content['form']['subject'] }}<input name="subject" value="{{ old('subject') }}" placeholder="Zapytanie o produkt" required></label>
                    <label>{{ $content['form']['message'] }}<textarea name="message" rows="5" placeholder="Opisz czego szukasz..." required>{{ old('message') }}</textarea></label>
                    @if($errors->any())
                        <div class="zl-alert zl-alert--error">{{ $errors->first() }}</div>
                    @endif
                    <button class="zl-button zl-button--primary" type="submit">{{ $content['form']['submit'] }} <span>→</span></button>
                </form>
                <aside class="zl-contact-side">
                    @foreach($referenceContactCards as $card)
                        <article class="zl-contact-card">
                            <span>{{ $card['icon'] }}</span>
                            <div>
                                <div>{{ $card['label'] }}</div>
                                <strong>{{ $card['value'] }}</strong>
                            </div>
                        </article>
                    @endforeach
                    <article class="zl-demo-callout">
                        <h2>Umów demo</h2>
                        <p>Pokaż nam swój proces obsługi zapytań. Znajdziemy razem, gdzie Zlecero najbardziej pomoże.</p>
                        <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">Zarezerwuj spotkanie</a>
                    </article>
                </aside>
            </div>
        </section>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
