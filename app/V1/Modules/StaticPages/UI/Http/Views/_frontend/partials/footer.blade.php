<footer class="zl-footer">
    <div class="zl-footer__inner">
        <div class="zl-footer__brand">
            <a class="zl-logo" href="{{ url('/'.$locale) }}">
                <span class="zl-logo__mark">Z</span>
                <span class="zl-logo__text">{{ $shared['brand'] }}</span>
            </a>
            <p>{{ $shared['footer']['description'] }}</p>
        </div>
        <div class="zl-footer__grid">
            <div>
                <div class="zl-footer__group-label">Produkt</div>
                <ul>
                    <li><a href="{{ url('/'.$locale.'#funkcje') }}">{{ $shared['nav']['features'] }}</a></li>
                    <li><a href="{{ url('/'.$locale.'/pricing') }}">{{ $shared['nav']['pricing'] }}</a></li>
                    <li><a href="{{ url('/'.$locale.'/faq') }}">{{ $shared['nav']['faq'] }}</a></li>
                </ul>
            </div>
            <div>
                <div class="zl-footer__group-label">Firma</div>
                <ul>
                    <li><a href="{{ url('/'.$locale.'/about') }}">{{ $shared['nav']['about'] }}</a></li>
                    <li><a href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="zl-footer__bottom">
        <span>{{ $shared['footer']['rights'] }}</span>
        <div class="zl-footer__legal">
            <a href="{{ $authLinks['login'] }}">{{ $shared['login'] }}</a>
        </div>
    </div>
</footer>
