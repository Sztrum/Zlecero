@extends('core::layouts.app')

@include('core::layouts.styles')
@include('core::layouts.scripts')

@section('head')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
@endsection

@section('main-content')
    <div class="static-pages-home" id="top">
        <nav class="static-pages-nav">
            <div class="static-pages-container static-pages-nav__inner">
                <x-static-pages::logo />

                <div class="static-pages-nav__links">
                    @foreach($navigationItems as $navigationItem)
                        <a href="{{ $navigationItem['href'] }}">
                            {{ $navigationItem['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="static-pages-nav__actions">
                    <div
                        class="static-pages-language-switcher"
                        aria-label="{{ __('static-pages::home.navigation_actions.language_label') }}"
                    >
                        @foreach($languageItems as $languageItem)
                            <a
                                href="{{ $languageItem['href'] }}"
                                @class([
                                    'is-active' => $languageItem['active'],
                                ])
                                hreflang="{{ $languageItem['locale'] }}"
                            >
                                {{ $languageItem['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ $navigationItems['login_href'] ?? '/auth/login' }}">
                        {{ __('static-pages::home.navigation_actions.login') }}
                    </a>
                    <x-static-pages::button
                        href="#cennik"
                        variant="nav"
                    >
                        {{ __('static-pages::home.navigation_actions.join_pilot') }}
                    </x-static-pages::button>
                </div>
            </div>
        </nav>

        <main>
            <section class="static-pages-hero">
                <div class="static-pages-container static-pages-hero__inner">
                    <p class="static-pages-hero__badge">
                        <span aria-hidden="true"></span>
                        {{ __('static-pages::home.hero.badge') }}
                    </p>
                    <h1>
                        {{ __('static-pages::home.hero.title_prefix') }}
                        <span>{{ __('static-pages::home.hero.title_accent') }}</span>
                    </h1>
                    <p class="static-pages-hero__lead">
                        {{ __('static-pages::home.hero.description') }}
                    </p>
                    <div class="static-pages-hero__actions">
                        <x-static-pages::button href="#cennik">
                            {{ __('static-pages::home.hero.primary_action') }}
                            <span aria-hidden="true">→</span>
                        </x-static-pages::button>
                        <x-static-pages::button
                            href="#jak-dziala"
                            variant="secondary"
                        >
                            <x-static-pages::icon name="circle" />
                            {{ __('static-pages::home.hero.secondary_action') }}
                        </x-static-pages::button>
                    </div>

                    <div class="static-pages-product-preview" aria-hidden="true">
                        <div class="static-pages-product-preview__bar">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="static-pages-product-preview__body">
                            <aside>
                                @foreach(__('static-pages::home.hero.preview_navigation') as $index => $label)
                                    <p @class(['is-active' => $index === 2])>
                                        <span></span>
                                        {{ $label }}
                                    </p>
                                @endforeach
                            </aside>
                            <div>
                                <div class="static-pages-product-preview__stats">
                                    @foreach($heroStats as $stat)
                                        <article>
                                            <strong>{{ $stat }}</strong>
                                            <span></span>
                                        </article>
                                    @endforeach
                                </div>
                                <div class="static-pages-product-preview__rows">
                                    @foreach([1, 2, 3] as $row)
                                        <article>
                                            <span></span>
                                            <span>
                                                <i></i>
                                                <i></i>
                                            </span>
                                            <span></span>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="static-pages-section static-pages-section--muted" id="dla-kogo">
                <div class="static-pages-container static-pages-container--narrow">
                    <x-static-pages::section-heading
                        :title="__('static-pages::home.problems.title')"
                        :description="__('static-pages::home.problems.description')"
                    />

                    <div class="static-pages-problem-grid">
                        @foreach($problemItems as $problemItem)
                            <x-static-pages::problem-card :item="$problemItem" />
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="static-pages-section" id="jak-dziala">
                <div class="static-pages-container static-pages-container--narrow">
                    <x-static-pages::section-heading
                        :title="__('static-pages::home.process.title')"
                        :description="__('static-pages::home.process.description')"
                    />

                    <div class="static-pages-process-grid">
                        @foreach($processSteps as $processStep)
                            <x-static-pages::process-step :step="$processStep" />
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="static-pages-section static-pages-section--muted" id="funkcje">
                <div class="static-pages-container static-pages-container--compact">
                    <x-static-pages::section-heading :title="__('static-pages::home.comparison.title')" />

                    <div class="static-pages-comparison-grid">
                        <x-static-pages::workflow-list
                            :items="$currentWorkflow"
                            :title="__('static-pages::home.comparison.current.title')"
                            variant="current"
                        />
                        <x-static-pages::workflow-list
                            :items="$zleceroWorkflow"
                            :title="__('static-pages::home.comparison.zlecero.title')"
                            variant="zlecero"
                        />
                    </div>
                </div>
            </section>

            <section class="static-pages-section" id="cennik">
                <div class="static-pages-container static-pages-container--compact">
                    <div class="static-pages-pilot">
                        <span>{{ __('static-pages::home.pilot.badge') }}</span>
                        <h2>{{ __('static-pages::home.pilot.title') }}</h2>
                        <p>{{ __('static-pages::home.pilot.description') }}</p>

                        <form class="static-pages-pilot-form" method="post" action="#">
                            @csrf
                            <div class="static-pages-pilot-form__grid">
                                @foreach($pilotFields as $field)
                                    <label>
                                        <span>{{ $field['label'] }}</span>
                                        <input
                                            name="{{ $field['name'] }}"
                                            placeholder="{{ $field['placeholder'] }}"
                                            type="{{ $field['type'] }}"
                                        >
                                    </label>
                                @endforeach
                            </div>
                            <label>
                                <span>{{ __('static-pages::home.pilot.message_label') }}</span>
                                <textarea
                                    name="message"
                                    placeholder="{{ __('static-pages::home.pilot.message_placeholder') }}"
                                    rows="2"
                                ></textarea>
                            </label>
                            <button type="submit">
                                {{ __('static-pages::home.pilot.submit') }}
                                <span aria-hidden="true">→</span>
                            </button>
                            <p>{{ __('static-pages::home.pilot.notice') }}</p>
                        </form>
                    </div>
                </div>
            </section>

            <section class="static-pages-section static-pages-section--muted" id="faq">
                <div class="static-pages-container static-pages-container--faq">
                    <x-static-pages::section-heading :title="__('static-pages::home.faq.title')" />

                    <div class="static-pages-faq-list">
                        @foreach($faqItems as $faqItem)
                            <x-static-pages::faq-item :faq="$faqItem" />
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <footer class="static-pages-footer">
            <div class="static-pages-container static-pages-footer__top">
                <div class="static-pages-footer__brand">
                    <x-static-pages::logo size="sm" />
                    <p>{{ __('static-pages::home.footer.description') }}</p>
                </div>
                <div class="static-pages-footer__columns">
                    @foreach($footerColumns as $footerColumn)
                        <div>
                            <p>{{ $footerColumn['title'] }}</p>
                            @foreach($footerColumn['links'] as $footerLink)
                                <a href="{{ $footerLink['href'] }}">
                                    {{ $footerLink['label'] }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="static-pages-container static-pages-footer__bottom">
                <p>{{ __('static-pages::home.footer.copyright') }}</p>
                <div>
                    <a href="/auth/login">{{ __('static-pages::home.navigation_actions.login') }}</a>
                    <a href="#cennik">{{ __('static-pages::home.navigation_actions.join_pilot') }}</a>
                </div>
            </div>
        </footer>
    </div>
@endsection
