<footer class="zp-footer">
    <div class="zp-footer__inner">
        <div>
            <a class="zp-logo zp-logo--dark" href="{{ url('/'.$locale) }}">
                <span class="zp-logo__mark">Z</span>
                <span>{{ $shared['brand'] }}</span>
            </a>
            <p>{{ $shared['footer']['description'] }}</p>
        </div>
        <div class="zp-footer__grid">
            <div>
                <strong>Produkt</strong>
                <a href="{{ url('/'.$locale.'#jak-dziala') }}">{{ $shared['nav']['how'] }}</a>
                <a href="{{ url('/'.$locale.'/pricing') }}">{{ $shared['nav']['pricing'] }}</a>
                <a href="{{ url('/'.$locale.'/faq') }}">{{ $shared['nav']['faq'] }}</a>
            </div>
            <div>
                <strong>Firma</strong>
                <a href="{{ url('/'.$locale.'/about') }}">{{ $shared['nav']['about'] }}</a>
                <a href="{{ url('/'.$locale.'/contact') }}">{{ $shared['nav']['contact'] }}</a>
            </div>
        </div>
    </div>
    <div class="zp-footer__bottom">
        <span>{{ $shared['footer']['rights'] }}</span>
        <a href="{{ $authLinks['login'] }}">{{ $shared['login'] }}</a>
    </div>
</footer>
