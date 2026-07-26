<nav class="zp-nav" aria-label="Primary">
    <div class="zp-nav__inner">
        <a class="zp-logo" href="{{ url('/'.$locale) }}" aria-label="{{ $shared['brand'] }}">
            <span class="zp-logo__mark">Z</span>
            <span>{{ $shared['brand'] }}</span>
        </a>
        <div class="zp-nav__links">
            <a href="{{ url('/'.$locale.'#jak-dziala') }}">{{ $shared['nav']['how'] }}</a>
            <a href="{{ url('/'.$locale.'#funkcje') }}">{{ $shared['nav']['features'] }}</a>
            <a href="{{ url('/'.$locale.'#dla-kogo') }}">{{ $shared['nav']['audience'] }}</a>
            <a href="{{ url('/'.$locale.'/pricing') }}">{{ $shared['nav']['pricing'] }}</a>
            <a href="{{ url('/'.$locale.'/faq') }}">{{ $shared['nav']['faq'] }}</a>
        </div>
        <div class="zp-nav__actions">
            <div class="zp-languages" aria-label="Language">
                @foreach($locales as $availableLocale)
                    <a @class(['is-active' => $availableLocale === $locale]) href="{{ url('/'.$availableLocale.($page === 'landing' ? '' : '/'.$page)) }}">{{ strtoupper($availableLocale) }}</a>
                @endforeach
            </div>
            <a class="zp-nav__login" href="{{ $authLinks['login'] }}">{{ $shared['login'] }}</a>
            <a class="zp-button zp-button--primary" href="{{ $authLinks['register'] }}">{{ $shared['trial_cta'] }}</a>
        </div>
    </div>
</nav>
