<?php

declare(strict_types=1);

return [
    'meta' => [
        'title' => 'Zamień zapytania z maila w zlecenia',
        'description' => 'Zlecero porządkuje wiadomości, załączniki, oferty i statusy realizacji dla małych i średnich firm.',
    ],
    'navigation' => [
        ['label' => 'Jak działa', 'href' => '#jak-dziala'],
        ['label' => 'Funkcje', 'href' => '#funkcje'],
        ['label' => 'Dla kogo', 'href' => '#dla-kogo'],
        ['label' => 'Cennik', 'href' => '#cennik'],
        ['label' => 'FAQ', 'href' => '#faq'],
    ],
    'navigation_actions' => [
        'login' => 'Zaloguj się',
        'join' => 'Dołącz',
        'join_pilot' => 'Dołącz do pilotażu',
        'language_label' => 'Wybór języka',
    ],
    'hero' => [
        'badge' => 'Trwa program pilotażowy - dołącz bezpłatnie',
        'title_prefix' => 'Każde zapytanie z maila zamień w',
        'title_accent' => 'uporządkowane zlecenie.',
        'description' => 'Zlecero porządkuje wiadomości, załączniki, oferty i statusy realizacji. Koniec z szukaniem plików, przeklejaniem maili i zastanawianiem się, kto miał odpowiedzieć klientowi.',
        'primary_action' => 'Dołącz do pilotażu',
        'secondary_action' => 'Zobacz, jak działa',
        'stats' => ['7 nowych', '4 oferty', '5 zleceń', '3 uwagi'],
        'preview_navigation' => ['Pulpit', 'Skrzynka', 'Zapytania', 'Oferty', 'Zlecenia'],
    ],
    'problems' => [
        'title' => 'Czy tak wygląda praca w Twojej firmie?',
        'description' => 'Problemy, które słyszymy od firm obsługujących zapytania przez e-mail.',
        'items' => [
            ['icon' => '📧', 'title' => 'Zapytania giną w skrzynce', 'description' => 'Klienci piszą, a maile toną w setkach innych wiadomości.'],
            ['icon' => '💾', 'title' => 'Pliki na kilku komputerach', 'description' => 'Nikt nie wie, gdzie jest aktualna wersja projektu.'],
            ['icon' => '❓', 'title' => 'Kto prowadzi klienta?', 'description' => 'Każdy myśli, że ktoś inny odpowiedział.'],
            ['icon' => '⏰', 'title' => 'Oferta czeka bez odpowiedzi', 'description' => 'Klient dostał ofertę tydzień temu. Nikt nie przypomniał.'],
            ['icon' => '🔍', 'title' => 'Trudno sprawdzić status', 'description' => 'Klient pyta, a Ty musisz szukać przez 10 minut.'],
            ['icon' => '📋', 'title' => 'Excel zamiast systemu', 'description' => 'Arkusze, których nikt nie aktualizuje na bieżąco.'],
        ],
    ],
    'process' => [
        'title' => 'Jak to działa?',
        'description' => 'Pięć kroków, które zastąpią e-mail, Excela i segregator.',
        'step_label' => 'KROK :number',
        'steps' => [
            ['number' => '1', 'label' => 'Klient wysyła wiadomość', 'icon' => 'mail', 'variant' => 'primary'],
            ['number' => '2', 'label' => 'Zlecero tworzy zapytanie', 'icon' => 'file-question', 'variant' => 'accent'],
            ['number' => '3', 'label' => 'Zespół przygotowuje ofertę', 'icon' => 'file-text', 'variant' => 'info'],
            ['number' => '4', 'label' => 'Klient akceptuje online', 'icon' => 'check-circle', 'variant' => 'success'],
            ['number' => '5', 'label' => 'Zapytanie staje się zleceniem', 'icon' => 'briefcase', 'variant' => 'warning'],
        ],
    ],
    'comparison' => [
        'title' => 'Dziś vs Zlecero',
        'current' => [
            'title' => 'Dzisiaj',
            'items' => [
                'E-mail, e-mail, e-mail...',
                'Excel z danymi klientów',
                'Telefon: "Gdzie jest ta oferta?"',
                'Foldery z plikami bez nazw',
                'Kartki i notatki',
                'Nikt nie wie, kto odpowiada',
            ],
        ],
        'zlecero' => [
            'title' => 'Zlecero',
            'items' => [
                'Jedno zapytanie, cała historia',
                'Panel klienta z ofertą online',
                'Jasny status każdego zlecenia',
                'Wszystkie pliki w jednym miejscu',
                'Automatyczne przypomnienia',
                'Jedna osoba odpowiedzialna',
            ],
        ],
    ],
    'pilot' => [
        'badge' => 'Program pilotażowy',
        'title' => 'Dołącz do pierwszych firm korzystających ze Zlecero.',
        'description' => 'Darmowe wdrożenie · Bezpłatny okres próbny · Wpływ na produkt · Preferencyjna cena',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Imię i nazwisko', 'placeholder' => 'Jan Kowalski'],
            ['name' => 'company', 'type' => 'text', 'label' => 'Firma', 'placeholder' => 'Twoja Firma Sp. z o.o.'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Adres e-mail', 'placeholder' => 'jan@firma.pl'],
            ['name' => 'phone', 'type' => 'tel', 'label' => 'Telefon', 'placeholder' => '+48 600 000 000'],
        ],
        'message_label' => 'Jak dziś obsługujecie zapytania klientów?',
        'message_placeholder' => 'E-mail, Excel, telefon...',
        'submit' => 'Zapisuję się do pilotażu',
        'notice' => 'Bez zobowiązań. Odpiszemy w ciągu 24 godzin.',
    ],
    'faq' => [
        'title' => 'Najczęstsze pytania',
        'items' => [
            ['question' => 'Czy muszę zmieniać skrzynkę e-mail?', 'answer' => 'Nie. Zlecero działa z Twoją aktualną skrzynką: Gmailem, Outlookiem lub dowolną skrzynką IMAP. Maile dalej trafiają do Ciebie, a Zlecero je porządkuje.'],
            ['question' => 'Czy działa z Gmailem i Microsoft 365?', 'answer' => 'Tak. Zlecero obsługuje integracje z oboma platformami. Konfiguracja zajmuje kilka minut.'],
            ['question' => 'Czy można dodać kilku pracowników?', 'answer' => 'Tak. Możesz zapraszać osoby z zespołu i nadawać im role właściciela, administratora, pracownika lub tylko podglądu.'],
            ['question' => 'Czy klient musi zakładać konto?', 'answer' => 'Nie. Klient otrzymuje link do oferty i może ją przejrzeć oraz zaakceptować bez rejestracji.'],
            ['question' => 'Czy Zlecero zastępuje program do faktur?', 'answer' => 'Nie. Zlecero porządkuje zapytania, oferty i zlecenia. Faktury wystawiasz w obecnym programie.'],
            ['question' => 'Ile kosztuje system po pilotażu?', 'answer' => 'Ceny zostaną ustalone na podstawie wyników programu pilotażowego. Uczestnicy pilotażu otrzymają preferencyjne warunki.'],
        ],
    ],
    'footer' => [
        'description' => 'System do zarządzania zapytaniami, ofertami i zleceniami dla małych i średnich firm.',
        'copyright' => '© 2026 Zlecero. Wszelkie prawa zastrzeżone.',
        'columns' => [
            ['title' => 'Produkt', 'links' => [
                ['label' => 'Jak działa', 'href' => '#jak-dziala'],
                ['label' => 'Funkcje', 'href' => '#funkcje'],
                ['label' => 'Cennik', 'href' => '#cennik'],
                ['label' => 'Pilotaż', 'href' => '#cennik'],
            ]],
            ['title' => 'Firma', 'links' => [
                ['label' => 'O nas', 'href' => '#top'],
                ['label' => 'Kontakt', 'href' => '#cennik'],
                ['label' => 'Blog', 'href' => '#top'],
            ]],
            ['title' => 'Prawne', 'links' => [
                ['label' => 'Regulamin', 'href' => '#top'],
                ['label' => 'Polityka prywatności', 'href' => '#top'],
                ['label' => 'Dane i bezpieczeństwo', 'href' => '#top'],
            ]],
        ],
    ],
];
