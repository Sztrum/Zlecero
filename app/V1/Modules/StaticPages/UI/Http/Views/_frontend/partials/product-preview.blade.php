<div class="zp-preview" aria-label="Zlecero application preview">
    <div class="zp-preview__chrome">
        <span></span><span></span><span></span>
        <i></i>
    </div>
    <div class="zp-preview__body">
        <aside>
            @foreach(['Pulpit', 'Skrzynka', 'Zapytania', 'Oferty', 'Zlecenia'] as $index => $label)
                <span @class(['is-active' => $index === 2])><i></i>{{ $label }}</span>
            @endforeach
        </aside>
        <main>
            <div class="zp-preview__stats">
                @foreach(['7 nowych', '4 oferty', '5 zleceń', '3 uwagi'] as $stat)
                    <div><strong>{{ $stat }}</strong><span></span></div>
                @endforeach
            </div>
            <div class="zp-preview__rows">
                @foreach([1, 2, 3] as $row)
                    <div><i></i><span><b></b><b></b></span><em></em></div>
                @endforeach
            </div>
        </main>
    </div>
</div>
