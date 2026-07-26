@extends('core::layouts.app')

@include('static_pages::_frontend.partials.head')

@section('main-content')
    <div class="zl-page">
        @include('static_pages::_frontend.partials.nav')
        <main class="zl-pricing-page">
            <div class="zl-section__inner">
                <header class="zl-reference-head zl-reference-head--center">
                    <h1>Wybierz plan Zlecero dopasowany do wielkości zespołu i liczby zapytań.</h1>
                    <p>Zacznij od podstaw, a gdy proces nabierze tempa — przejdź na wyższy plan w dowolnym momencie.</p>
                </header>

                <section class="zl-pricing-main">
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
                </section>

                <section class="zl-compare">
                    <div class="zl-compare__head">
                        <h2>Porównaj funkcje i wybierz zakres, który odpowiada potrzebom Twojej firmy.</h2>
                        <p>Wszystkie ceny netto, rozliczane miesięcznie.</p>
                    </div>
                    <div class="zl-compare__scroll">
                        <table>
                            <thead>
                            <tr>
                                <th>Funkcja</th>
                                <th>Light</th>
                                <th>Starter</th>
                                <th>Professional</th>
                                <th>Enterprise</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($referencePricingComparison as $row)
                                <tr>
                                    <td>{{ $row['feature'] }}</td>
                                    @foreach(['light', 'starter', 'professional', 'enterprise'] as $column)
                                        <td @class(['is-primary' => $column === 'professional'])>{{ $row[$column] }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
        @include('static_pages::_frontend.partials.footer')
    </div>
@endsection

@include('core::layouts.scripts')
