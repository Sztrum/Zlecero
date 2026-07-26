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
                        <a class="zp-button zp-button--primary zp-button--large" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
                        <a class="zp-button zp-button--secondary zp-button--large" href="#jak-dziala">{{ $shared['demo_cta'] }}</a>
                    </div>
                </div>
                @include('static_pages::_frontend.partials.product-preview')
            </div>
        </section>

        <section id="funkcje" class="zp-section zp-section--white zp-reference-intro">
            <div class="zp-section__head">
                <h2>Od pierwszego zapytania do realizacji prowadź każdą sprawę w jednym, czytelnym procesie.</h2>
            </div>
            <div class="zp-product-scenes">
                @foreach([
                    ['number' => '01', 'title' => 'Zamień chaos wiadomości w jedną kolejkę spraw.', 'text' => 'E-maile, załączniki i brakujące dane przestają ginąć w skrzynkach zespołu.'],
                    ['number' => '02', 'title' => 'Zobacz kompletne zapytanie, zanim przygotujesz ofertę.', 'text' => 'Klient, termin, priorytet i pliki są od razu uporządkowane w jednej karcie.'],
                    ['number' => '03', 'title' => 'Przygotuj ofertę, którą klient zaakceptuje bez zbędnej wymiany wiadomości.', 'text' => 'Zakres, warunki i wersje oferty są w jednym dokumencie gotowym do wysłania.'],
                    ['number' => '04', 'title' => 'Prowadź zlecenie do końca, bez utraty kontekstu.', 'text' => 'Status, opiekun, termin i komunikacja pozostają przy tej samej sprawie aż do realizacji.'],
                ] as $scene)
                    <article>
                        <span>{{ $scene['number'] }}</span>
                        <div>
                            <h3>{{ $scene['title'] }}</h3>
                            <p>{{ $scene['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="jak-dziala" class="zp-section zp-process-band">
            <div>
                <h2>Zlecero prowadzi zespół od pierwszej wiadomości do realizacji zlecenia.</h2>
                <div class="zp-process-list">
                    @foreach([
                        ['label' => $content['steps'][0]['label'], 'text' => 'Wiadomość trafia do wspólnej kolejki.'],
                        ['label' => $content['steps'][1]['label'], 'text' => 'Dane klienta i pliki są od razu przy sprawie.'],
                        ['label' => $content['steps'][2]['label'], 'text' => 'Zespół tworzy ofertę na bazie uporządkowanego kontekstu.'],
                        ['label' => $content['steps'][3]['label'], 'text' => 'Klient podejmuje decyzję online.'],
                        ['label' => $content['steps'][4]['label'], 'text' => 'Zaakceptowana oferta przechodzi do realizacji.'],
                    ] as $index => $step)
                        <article>
                            <span>0{{ $index + 1 }}</span>
                            <strong>{{ $step['label'] }}</strong>
                            <p>{{ $step['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
            @include('static_pages::_frontend.partials.product-preview')
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

        <section id="cennik" class="zp-section zp-section--white">
            <div class="zp-section__head">
                <h2>Znajdź plan Zlecero, który dopasuje się do skali Twojej sprzedaży.</h2>
                <p>14 dni bezpłatnie. Bez karty kredytowej.</p>
            </div>
            <div class="zp-pricing">
                @foreach($pricingPreview['plans'] as $plan)
                    <article>
                        <h2>{{ $plan['name'] }}</h2>
                        <strong>{{ $plan['price'] }}</strong>
                        <p>{{ $plan['caption'] }}</p>
                        @foreach($plan['features'] as $feature)
                            <span>✓ {{ $feature }}</span>
                        @endforeach
                        <a class="zp-button zp-button--primary" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="zp-section zp-faq-preview">
            <div class="zp-section__head">
                <h2>Poznaj najczęstsze pytania i odpowiedzi dotyczące pracy z Zlecero.</h2>
            </div>
            <div class="zp-faq-list">
                @foreach(array_slice($faqPreview['sections'][0]['items'], 0, 2) as $item)
                    <article>
                        <h3>{{ $item['question'] }}</h3>
                        <p>{{ $item['answer'] }}</p>
                    </article>
                @endforeach
                @foreach(array_slice($faqPreview['sections'][1]['items'], 0, 2) as $item)
                    <article>
                        <h3>{{ $item['question'] }}</h3>
                        <p>{{ $item['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="zp-pilot zp-final-cta">
            <div>
                <span>{{ $shared['pilot_badge'] }}</span>
                <h2>Szybciej odpowiadaj na zapytania i skuteczniej zamieniaj je w zlecenia.</h2>
                <p>14 dni bezpłatnie. Pełne funkcje Professional. Bez karty kredytowej.</p>
                <a class="zp-button zp-button--light zp-button--large" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
            </div>
        </section>

        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
