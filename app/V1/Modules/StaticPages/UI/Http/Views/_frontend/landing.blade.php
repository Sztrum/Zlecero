@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zl-page">
        <nav class="zl-nav" aria-label="Primary">
            <div class="zl-nav__inner">
                <a class="zl-logo" href="{{ url('/'.$locale) }}" aria-label="{{ $shared['brand'] }}">
                    <span class="zl-logo__mark">Z</span>
                    <span class="zl-logo__text">{{ $shared['brand'] }}</span>
                </a>
                <div class="zl-nav__links">
                    <a href="{{ url('/'.$locale.'#funkcje') }}">Funkcje</a>
                    <a href="{{ url('/'.$locale.'/pricing') }}">Cennik</a>
                    <a href="{{ url('/'.$locale.'/faq') }}">FAQ</a>
                    <a href="{{ url('/'.$locale.'/contact') }}">Kontakt</a>
                </div>
                <div class="zl-nav__actions">
                    <a class="zl-nav__login" href="{{ $authLinks['login'] }}">Zaloguj się</a>
                    <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">Wypróbuj za darmo</a>
                </div>
                <details class="zl-mobile-menu">
                    <summary class="zl-mobile-toggle" aria-label="Menu">☰</summary>
                    <div class="zl-mobile-menu__panel">
                        <a href="{{ url('/'.$locale.'#funkcje') }}">Funkcje</a>
                        <a href="{{ url('/'.$locale.'/pricing') }}">Cennik</a>
                        <a href="{{ url('/'.$locale.'/faq') }}">FAQ</a>
                        <a href="{{ url('/'.$locale.'/contact') }}">Kontakt</a>
                        <a href="{{ $authLinks['login'] }}">Zaloguj się</a>
                        <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">Wypróbuj za darmo</a>
                    </div>
                </details>
            </div>
        </nav>

        <section class="zl-hero">
            <div class="zl-hero__inner">
                <div class="zl-hero__copy">
                    <h1>Zlecero zamienia zapytania w oferty i podpisane zlecenia.</h1>
                    <p>Zlecero to platforma SaaS dla firm, które chcą szybciej obsługiwać zapytania, automatycznie tworzyć oferty i budować trwałe relacje z klientami.</p>
                    <div class="zl-flow" aria-label="Etapy procesu">
                        <span>E-mail</span>
                        <span class="zl-flow__arrow">→</span>
                        <span>zapytanie</span>
                        <span class="zl-flow__arrow">→</span>
                        <span>oferta</span>
                        <span class="zl-flow__arrow">→</span>
                        <span>zlecenie</span>
                    </div>
                    <div class="zl-hero__actions">
                        <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">Zacznij bezpłatnie <span>→</span></a>
                        <a class="zl-button zl-button--ghost-dark" href="{{ url('/'.$locale.'/pricing') }}">Zobacz cennik</a>
                    </div>
                </div>
                <div class="zl-hero__demo">
                        <div class="zl-demo" data-hero-phase="0" aria-label="Demonstracja zamiany e-maila w zapytanie">
                        <div class="zl-demo__window">
                            <div class="zl-window-bar">
                                <span class="zl-window-dot zl-window-dot--red"></span>
                                <span class="zl-window-dot zl-window-dot--yellow"></span>
                                <span class="zl-window-dot zl-window-dot--green"></span>
                                <span class="zl-window-url">app.zlecero.pl</span>
                            </div>
                            <div class="zl-demo__topline">
                                <div class="zl-demo__brand">
                                    <span class="zl-demo__brand-icon">Z</span>
                                    <span>Zlecero</span>
                                </div>
                                <span class="zl-demo__caption">Automatyczne przetwarzanie</span>
                            </div>
                            <div class="zl-demo__stage">
                                <div class="zl-demo__mailbox">
                                    <div class="zl-demo__label">Nowe wiadomości</div>
                                    @foreach([
                                        ['sender' => 'Techno Systems', 'subject' => 'Wycena integracji API z ERP', 'attachment' => 'brief.pdf'],
                                        ['sender' => 'BuildCraft Polska', 'subject' => 'Dostawa materiałów Q3', 'attachment' => 'specyfikacja.xlsx'],
                                        ['sender' => 'Marta Kowalska', 'subject' => 'Projekt strony', 'attachment' => 'makieta.pdf'],
                                    ] as $mail)
                                        <article class="zl-mail">
                                            <div class="zl-mail__row">
                                                <span class="zl-mail__sender">{{ $mail['sender'] }}</span>
                                                <span class="zl-mail__time">teraz</span>
                                            </div>
                                            <div class="zl-mail__subject">{{ $mail['subject'] }}</div>
                                            <div class="zl-mail__attachment">▣ {{ $mail['attachment'] }}</div>
                                        </article>
                                    @endforeach
                                </div>
                                <article class="zl-inquiry-card">
                                    <div class="zl-inquiry-card__head">
                                        <div>
                                            <div class="zl-inquiry-card__eyebrow">Nowe zapytanie</div>
                                            <div class="zl-inquiry-card__id">ZAP-2026-089</div>
                                        </div>
                                        <span class="zl-badge zl-badge--blue" data-zl-hero-badge>Nowe</span>
                                    </div>
                                    <div class="zl-inquiry-card__grid">
                                        <div class="zl-field">
                                            <div class="zl-field__label">Klient</div>
                                            <div class="zl-field__value">Techno Systems</div>
                                        </div>
                                        <div class="zl-field">
                                            <div class="zl-field__label">Termin</div>
                                            <div class="zl-field__value">25 lipca</div>
                                        </div>
                                    </div>
                                    <div class="zl-field zl-field--wide">
                                        <div class="zl-field__label">Temat</div>
                                        <div class="zl-field__value">Wycena integracji API z ERP</div>
                                    </div>
                                    <div class="zl-inquiry-card__foot">
                                        <span class="zl-inquiry-card__file">▣ brief.pdf</span>
                                        <span class="zl-inquiry-card__ready">✓ Dane rozpoznane</span>
                                    </div>
                                </article>
                            </div>
                            <div class="zl-demo__status">
                                <span>✦ <span data-zl-hero-status>Czekam na nowe zapytanie</span></span>
                            </div>
                        </div>
                        <div class="zl-demo__pill">
                            <div class="zl-demo__pill-inner"><span class="zl-dot"></span><span data-zl-hero-pill>Analiza zapytania</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="funkcje" class="zl-section zl-section--white">
            <div class="zl-section__inner">
                <h2 class="zl-section__title">Od pierwszego zapytania do realizacji prowadź każdą sprawę w jednym, czytelnym procesie.</h2>
            </div>
            <div class="zl-process-story">
                <div class="zl-process-story__sticky">
                    <div class="zl-process-story__grid">
                        <div class="zl-timeline">
                            @foreach($landingReference['timeline'] as $index => $step)
                                <article @class(['zl-timeline__item', 'is-active' => $index === 0]) data-landing-step="{{ $index }}">
                                    <div class="zl-timeline__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                                    <span class="zl-timeline__dot"></span>
                                    @if($index < count($landingReference['timeline']) - 1)
                                        <span class="zl-timeline__line"></span>
                                    @endif
                                    <div class="zl-timeline__label">{{ $step['label'] }}</div>
                                    <h3>{{ $step['title'] }}</h3>
                                    <p>{{ $step['desc'] }}</p>
                                </article>
                            @endforeach
                        </div>
                        <article class="zl-process-card zl-process-card--story" aria-label="Podgląd procesu Zlecero">
                            <div class="zl-process-card__head">
                                <div class="zl-demo__brand">
                                    <span class="zl-demo__brand-icon">Z</span>
                                    <span>Zlecero</span>
                                </div>
                                <span class="zl-demo__caption">ZAP-2026-089</span>
                            </div>
                            <div class="zl-process-card__body">
                                <span class="zl-process-card__rail"></span>
                                <div class="zl-process-card__content">
                                    <div class="zl-process-card__state">
                                        <div>
                                            <div class="zl-process-card__meta">Aktualny etap</div>
                                            <div class="zl-process-card__stage" data-zl-stage>E-mail</div>
                                        </div>
                                        <span class="zl-badge zl-badge--blue" data-zl-status>Nowa wiadomość</span>
                                    </div>
                                    <div class="zl-process-card__subject">
                                        <div class="zl-process-card__subject-row">
                                            <span class="zl-process-card__icon" data-zl-icon>✉</span>
                                            <div>
                                                <div class="zl-process-card__client">Techno Systems Sp. z o.o.</div>
                                                <div class="zl-process-card__topic">Wycena integracji API z ERP</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="zl-process-card__facts">
                                        <div class="zl-fact">
                                            <span>Termin</span>
                                            <strong>25 lipca 2026</strong>
                                        </div>
                                        <div class="zl-fact">
                                            <span>Załącznik</span>
                                            <strong>▣ brief.pdf</strong>
                                        </div>
                                    </div>
                                    <div class="zl-process-card__progress">
                                        <span class="zl-dot"></span>
                                        <span data-zl-progress>Analiza wiadomości</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <div class="zl-mobile-steps">
                            @foreach($landingReference['timeline'] as $index => $step)
                                <article class="zl-mobile-step">
                                    <div class="zl-mobile-step__row">
                                        <div class="zl-mobile-step__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                                        <div>
                                            <div class="zl-mobile-step__label">{{ $step['label'] }}</div>
                                            <h3>{{ $step['title'] }}</h3>
                                            <p>{{ $step['desc'] }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="zl-section zl-section--cream">
            <div class="zl-benefit-grid">
                <div class="zl-benefits">
                    <h2>Zlecero prowadzi zespół od pierwszej wiadomości do realizacji zlecenia.</h2>
                    <div class="zl-benefits__list">
                        @foreach($landingReference['benefits'] as $index => $benefit)
                            <button type="button" @class(['zl-benefit', 'is-active' => $index === 0]) data-benefit-index="{{ $index }}">
                                <div class="zl-benefit__row">
                                    <span class="zl-benefit__number">0{{ $index + 1 }}</span>
                                    <div>
                                        <h3>{{ $benefit['title'] }}</h3>
                                        <p>{{ $benefit['text'] }}</p>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
                <article class="zl-process-card zl-process-card--benefits" aria-label="Podgląd korzyści Zlecero">
                    <div class="zl-process-card__head">
                        <div class="zl-demo__brand">
                            <span class="zl-demo__brand-icon">Z</span>
                            <span>Zlecero</span>
                        </div>
                        <span class="zl-demo__caption">ZAP-2026-089</span>
                    </div>
                    <div class="zl-process-card__body">
                        <span class="zl-process-card__rail"></span>
                        <div class="zl-process-card__content">
                            <div class="zl-process-card__state">
                                <div>
                                    <div class="zl-process-card__meta">Aktualny etap</div>
                                    <div class="zl-process-card__stage" data-zl-stage>Zapytanie</div>
                                </div>
                                <span class="zl-badge zl-badge--primary" data-zl-status>Nowe</span>
                            </div>
                            <div class="zl-process-card__subject">
                                <div class="zl-process-card__subject-row">
                                    <span class="zl-process-card__icon" data-zl-icon>▣</span>
                                    <div>
                                        <div class="zl-process-card__client">Techno Systems Sp. z o.o.</div>
                                        <div class="zl-process-card__topic">Wycena integracji API z ERP</div>
                                    </div>
                                </div>
                            </div>
                            <div class="zl-process-card__facts">
                                <div class="zl-fact">
                                    <span>Termin</span>
                                    <strong>25 lipca 2026</strong>
                                </div>
                                <div class="zl-fact">
                                    <span>Załącznik</span>
                                    <strong>▣ brief.pdf</strong>
                                </div>
                            </div>
                            <div class="zl-process-card__progress">
                                <span class="zl-dot"></span>
                                <span data-zl-progress>Dane są przygotowane do kolejnego kroku</span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="zl-section--pricing">
            <div class="zl-section__inner">
                <div class="zl-pricing-head">
                    <h2>Znajdź plan Zlecero, który dopasuje się do skali Twojej sprzedaży.</h2>
                    <p>14 dni bezpłatnie. Bez karty kredytowej.</p>
                </div>
                <div class="zl-plan-grid">
                    @foreach($landingReference['pricing'] as $plan)
                        <article @class(['zl-plan', 'zl-plan--highlight' => $plan['highlight']])>
                            @if($plan['highlight'])
                                <div class="zl-plan__popular">Najpopularniejszy</div>
                            @endif
                            <div class="zl-plan__name">{{ $plan['name'] }}</div>
                            <div class="zl-plan__price">
                                @if($plan['price'] !== null)
                                    <strong>{{ $plan['price'] }} zł</strong>
                                    <span>{{ $plan['period'] }}</span>
                                @else
                                    <strong>Indywidualnie</strong>
                                @endif
                            </div>
                            <p class="zl-plan__desc">{{ $plan['desc'] }}</p>
                            <a class="zl-button {{ $plan['highlight'] ? 'zl-button--light' : 'zl-button--primary' }}" href="{{ $plan['name'] === 'Enterprise' ? url('/'.$locale.'/contact') : $authLinks['register'] }}">{{ $plan['cta'] }}</a>
                            <ul class="zl-plan__features">
                                @foreach($plan['features'] as $feature)
                                    <li><span>✓</span><span>{{ $feature }}</span></li>
                                @endforeach
                                @foreach($plan['missing'] as $feature)
                                    <li class="is-muted"><span>×</span><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
                <a class="zl-pricing-more" href="{{ url('/'.$locale.'/pricing') }}">Pełne porównanie planów <span>→</span></a>
            </div>
        </section>

        <section class="zl-faq-preview">
            <div class="zl-faq-preview__inner">
                <div class="zl-faq-preview__head">
                    <h2>Poznaj najczęstsze pytania i odpowiedzi dotyczące pracy z Zlecero.</h2>
                </div>
                <div class="zl-faq-list">
                    @foreach($landingReference['faqs'] as $index => $faq)
                        <article class="zl-faq-item">
                            <button class="zl-faq-item__button" type="button" aria-expanded="false">
                                <span>{{ $faq['q'] }}</span>
                                <span class="zl-faq-item__icon">+</span>
                            </button>
                            <div class="zl-faq-item__answer">
                                <div>
                                    <p>{{ $faq['a'] }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <a class="zl-faq-more" href="{{ url('/'.$locale.'/faq') }}">Wszystkie pytania i odpowiedzi <span>→</span></a>
            </div>
        </section>

        <section class="zl-cta">
            <div class="zl-cta__inner">
                <div class="zl-cta__mini">
                    <div class="zl-cta__mini-row">
                        <div>
                            <div class="zl-cta__mini-kicker">Zlecenie ZLE-2026-021</div>
                            <div class="zl-cta__mini-title">Integracja API z ERP</div>
                        </div>
                        <span class="zl-cta__badge">✓ Zrealizowane</span>
                    </div>
                </div>
                <div class="zl-cta__content">
                    <h2>Szybciej odpowiadaj na zapytania i skuteczniej zamieniaj je w zlecenia.</h2>
                    <p>14 dni bezpłatnie. Pełne funkcje Professional. Bez karty kredytowej.</p>
                    <div class="zl-cta__actions">
                        <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">Zacznij bezpłatny trial <span>→</span></a>
                        <a class="zl-button zl-button--ghost-dark" href="{{ url('/'.$locale.'/contact') }}">Umów demo</a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="zl-footer">
            <div class="zl-footer__inner">
                <div class="zl-footer__brand">
                    <a class="zl-logo" href="{{ url('/'.$locale) }}">
                        <span class="zl-logo__mark">Z</span>
                        <span class="zl-logo__text">Zlecero</span>
                    </a>
                    <p>Platforma SaaS do zarządzania zapytaniami, ofertami i klientami dla małych i średnich firm.</p>
                </div>
                <div>
                    <div class="zl-footer__group-label">Produkt</div>
                    <ul>
                        <li><a href="{{ url('/'.$locale.'#funkcje') }}">Funkcje</a></li>
                        <li><a href="{{ url('/'.$locale.'/pricing') }}">Cennik</a></li>
                        <li><a href="{{ url('/'.$locale.'/faq') }}">FAQ</a></li>
                        <li><a href="{{ url('/'.$locale.'/contact') }}">Kontakt</a></li>
                    </ul>
                </div>
            </div>
            <div class="zl-footer__bottom">
                <span>© 2026 Zlecero. Wszelkie prawa zastrzeżone.</span>
                <div class="zl-footer__legal">
                    <a href="{{ url('/'.$locale.'/contact') }}">Polityka prywatności</a>
                    <a href="{{ url('/'.$locale.'/contact') }}">Regulamin</a>
                    <a href="{{ url('/'.$locale.'/contact') }}">RODO</a>
                </div>
            </div>
        </footer>
    </div>
@endsection

@include('core::layouts.scripts')
