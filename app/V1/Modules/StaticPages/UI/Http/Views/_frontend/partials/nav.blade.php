<nav class="zl-nav" aria-label="Primary">
    <div class="zl-nav__inner">
        <a class="zl-logo" href="{{ url('/'.$locale) }}" aria-label="{{ $shared['brand'] }}">
            <span class="zl-logo__mark">Z</span>
            <span class="zl-logo__text">{{ $shared['brand'] }}</span>
        </a>
        <div class="zl-nav__links">
            <a href="{{ url('/'.$locale.'#funkcje') }}">{{ $shared['nav']['features'] }}</a>
            <a href="{{ url('/'.$locale.'/pricing') }}">{{ $shared['nav']['pricing'] }}</a>
            <a href="{{ url('/'.$locale.'/faq') }}">{{ $shared['nav']['faq'] }}</a>
            <a href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a>
        </div>
        <div class="zl-nav__actions">
            <div class="zl-languages" aria-label="Language">
                @foreach($locales as $availableLocale)
                    <a @class(['is-active' => $availableLocale === $locale]) href="{{ url('/'.$availableLocale.($page === 'landing' ? '' : '/'.$page)) }}">{{ strtoupper($availableLocale) }}</a>
                @endforeach
            </div>
            <a class="zl-nav__login" href="{{ $authLinks['login'] }}">{{ $shared['login'] }}</a>
            <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
        </div>
        <details class="zl-mobile-menu">
            <summary class="zl-mobile-toggle" aria-label="Menu">☰</summary>
            <div class="zl-mobile-menu__panel">
                <a href="{{ url('/'.$locale.'#funkcje') }}">{{ $shared['nav']['features'] }}</a>
                <a href="{{ url('/'.$locale.'/pricing') }}">{{ $shared['nav']['pricing'] }}</a>
                <a href="{{ url('/'.$locale.'/faq') }}">{{ $shared['nav']['faq'] }}</a>
                <a href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a>
                <a href="{{ $authLinks['login'] }}">{{ $shared['login'] }}</a>
                <a class="zl-button zl-button--primary" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
                <span class="zl-mobile-menu__langs">
                    @foreach($locales as $availableLocale)
                        <a @class(['is-active' => $availableLocale === $locale]) href="{{ url('/'.$availableLocale.($page === 'landing' ? '' : '/'.$page)) }}">{{ strtoupper($availableLocale) }}</a>
                    @endforeach
                </span>
            </div>
        </details>
    </div>
</nav>
