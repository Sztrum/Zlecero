<?php

declare(strict_types=1);

return [
    'shared' => [
        'brand' => 'Zlecero',
        'pilot_badge' => 'Trwa program pilotażowy - dołącz bezpłatnie',
        'login' => 'Zaloguj się',
        'trial_cta' => 'Dołącz do pilotażu',
        'demo_cta' => 'Zobacz, jak działa',
        'nav' => [
            'how' => 'Jak działa',
            'features' => 'Funkcje',
            'audience' => 'Dla kogo',
            'pricing' => 'Cennik',
            'faq' => 'FAQ',
            'about' => 'O firmie',
            'contact' => 'Kontakt',
        ],
        'footer' => [
            'description' => 'System do zarządzania zapytaniami, ofertami i zleceniami dla małych i średnich firm.',
            'rights' => '© 2026 Zlecero. Wszelkie prawa zastrzeżone.',
        ],
    ],
    'contact' => [
        'sent' => 'Dziękujemy. Zgłoszenie zostało przyjęte i wrócimy z odpowiedzią.',
        'duplicate' => 'To zgłoszenie zostało już przyjęte. Nie wysyłamy duplikatu.',
        'failed' => 'Nie udało się przekazać zgłoszenia. Spróbuj ponownie za chwilę.',
    ],
    'meta' => [
        'landing' => [
            'title' => 'Zlecero - e-mail, zapytania, oferty i zlecenia w jednym miejscu',
            'description' => 'Zlecero porządkuje zapytania klientów z e-maila, pomaga przygotować ofertę i automatycznie zamienia akceptację w zlecenie.',
        ],
        'pricing' => [
            'title' => 'Cennik Zlecero - pakiety i program pilotażowy',
            'description' => 'Poznaj plany Zlecero, limity użytkowników, skrzynek i plików oraz zasady programu pilotażowego.',
        ],
        'faq' => [
            'title' => 'FAQ Zlecero - pytania przed rejestracją',
            'description' => 'Odpowiedzi o integracji skrzynki, bezpieczeństwie, okresie próbnym, limitach, AI i wdrożeniu Zlecero.',
        ],
        'about' => [
            'title' => 'O Zlecero - prostsza obsługa zapytań i zleceń',
            'description' => 'Zlecero powstaje dla małych i średnich firm, które chcą uporządkować obsługę zapytań, ofert i realizacji.',
        ],
        'contact' => [
            'title' => 'Kontakt Zlecero - zgłoś firmę do pilotażu',
            'description' => 'Skontaktuj się z Zlecero, zgłoś firmę do programu pilotażowego lub zadaj pytanie przed rejestracją.',
        ],
    ],
    'pages' => [
        'landing' => [
            'hero_title' => 'Każde zapytanie z maila zamień w uporządkowane zlecenie.',
            'hero_highlight' => 'uporządkowane zlecenie.',
            'hero_text' => 'Zlecero porządkuje wiadomości, załączniki, oferty i statusy realizacji. Koniec z szukaniem plików, przeklejaniem maili i zastanawianiem się, kto miał odpowiedzieć klientowi.',
            'problem_title' => 'Czy tak wygląda praca w Twojej firmie?',
            'problem_text' => 'Problemy, które codziennie pojawiają się w firmach pracujących na mailu i Excelu.',
            'problems' => [
                ['icon' => 'mail', 'title' => 'Zapytania giną w skrzynce', 'text' => 'Klienci piszą, a maile toną w setkach innych wiadomości.'],
                ['icon' => 'folder', 'title' => 'Pliki na kilku komputerach', 'text' => 'Nikt nie wie, gdzie jest aktualna wersja projektu.'],
                ['icon' => 'users', 'title' => 'Kto prowadzi klienta?', 'text' => 'Każdy myśli, że ktoś inny odpowiedział lub wysłał ofertę.'],
                ['icon' => 'clock', 'title' => 'Oferta czeka bez odpowiedzi', 'text' => 'Klient dostał ofertę tydzień temu. Nikt nie przypomniał.'],
                ['icon' => 'search', 'title' => 'Trudno sprawdzić status', 'text' => 'Klient pyta, a zespół musi szukać informacji przez kilka minut.'],
                ['icon' => 'sheet', 'title' => 'Excel zamiast procesu', 'text' => 'Arkusze i notatki nie nadążają za codzienną pracą zespołu.'],
            ],
            'how_title' => 'Jak to działa?',
            'how_text' => 'Pięć kroków, które zastępują chaos w mailu, Excelu i folderach.',
            'steps' => [
                ['label' => 'Klient wysyła wiadomość'],
                ['label' => 'Zlecero tworzy zapytanie'],
                ['label' => 'Zespół przygotowuje ofertę'],
                ['label' => 'Klient akceptuje online'],
                ['label' => 'Zapytanie staje się zleceniem'],
            ],
            'comparison_title' => 'Dzisiaj vs Zlecero',
            'today' => ['E-mail, e-mail, e-mail...', 'Excel z danymi klientów', 'Telefon: "Gdzie jest ta oferta?"', 'Foldery z plikami bez nazw', 'Kartki i notatki', 'Nikt nie wie, kto odpowiada'],
            'zlecero' => ['Jedno zapytanie, cała historia', 'Panel klienta z ofertą online', 'Jasny status każdego zlecenia', 'Wszystkie pliki w jednym miejscu', 'Automatyczne przypomnienia', 'Jedna osoba odpowiedzialna'],
            'pilot_title' => 'Dołącz do pierwszych firm korzystających ze Zlecero.',
            'pilot_text' => 'Darmowe wdrożenie · Bezpłatny okres próbny · Wpływ na produkt · Preferencyjna cena',
            'faq_title' => 'Najczęstsze pytania',
        ],
        'pricing' => [
            'title' => 'Prosty cennik na start i jasna ścieżka po pilotażu.',
            'lead' => 'Pakiety są przygotowane dla małych zespołów, które chcą uporządkować zapytania bez wdrażania ciężkiego CRM-a.',
            'plans' => [
                ['name' => 'Pilot', 'price' => '0 zł', 'caption' => 'dla pierwszych firm', 'features' => ['do 5 użytkowników', '1 skrzynka e-mail', '10 GB plików', 'wsparcie wdrożeniowe']],
                ['name' => 'Team', 'price' => '149 zł', 'caption' => 'miesięcznie po pilotażu', 'features' => ['do 10 użytkowników', '2 skrzynki e-mail', '50 GB plików', 'oferty PDF i zlecenia']],
                ['name' => 'Business', 'price' => 'Indywidualnie', 'caption' => 'dla większych procesów', 'features' => ['więcej skrzynek', 'niestandardowe limity', 'priorytetowe wsparcie', 'integracje na zamówienie']],
            ],
            'billing_faq' => [
                ['question' => 'Czy mogę zmienić pakiet?', 'answer' => 'Tak. Struktura jest przygotowana do zmiany planu bez migracji danych.'],
                ['question' => 'Czy pilot jest płatny?', 'answer' => 'Nie. Program pilotażowy jest bezpłatny dla firm przyjętych do testów.'],
                ['question' => 'Co po okresie próbnym?', 'answer' => 'Uczestnicy otrzymają propozycję dalszego korzystania z preferencyjną ceną.'],
            ],
        ],
        'faq' => [
            'title' => 'Najczęstsze pytania przed rejestracją',
            'lead' => 'Krótko i konkretnie o skrzynkach, bezpieczeństwie, AI, limitach i wdrożeniu.',
            'sections' => [
                ['title' => 'Skrzynka i wdrożenie', 'items' => [
                    ['slug' => 'czy-musze-zmieniac-skrzynke', 'question' => 'Czy muszę zmieniać skrzynkę e-mail?', 'answer' => 'Nie. Zlecero działa obok obecnej skrzynki i porządkuje wiadomości w proces zapytań.'],
                    ['slug' => 'ile-trwa-wdrozenie', 'question' => 'Ile trwa wdrożenie?', 'answer' => 'Podstawową konfigurację można przejść w jednym spotkaniu. Szczegóły integracji zależą od skrzynki i liczby użytkowników.'],
                ]],
                ['title' => 'Bezpieczeństwo i AI', 'items' => [
                    ['slug' => 'czy-dane-sa-bezpieczne', 'question' => 'Czy dane klientów są bezpieczne?', 'answer' => 'Dostęp w aplikacji jest ograniczony do firmy użytkownika, a dane operacyjne są izolowane po stronie API.'],
                    ['slug' => 'czy-ai-jest-wymagane', 'question' => 'Czy AI jest wymagane?', 'answer' => 'Nie. MVP skupia się na uporządkowaniu procesu; automatyzacje i AI mogą być rozszerzane etapami.'],
                ]],
                ['title' => 'Limity i rozliczenia', 'items' => [
                    ['slug' => 'jakie-sa-limity', 'question' => 'Jakie są limity?', 'answer' => 'W pilotażu limity ustalamy praktycznie: użytkownicy, skrzynki i pliki mają wystarczyć do codziennej pracy małego zespołu.'],
                    ['slug' => 'czy-klient-zaklada-konto', 'question' => 'Czy klient musi zakładać konto?', 'answer' => 'Nie. Klient może otrzymać link do oferty i zaakceptować ją bez konta w aplikacji.'],
                ]],
            ],
        ],
        'about' => [
            'title' => 'Zlecero powstaje dla firm, które żyją z szybkich odpowiedzi.',
            'lead' => 'Budujemy prosty system dla zespołów, które mają za dużo zapytań w e-mailu, za dużo plików w folderach i za mało jasności, co dzieje się dalej.',
            'blocks' => [
                ['title' => 'Misja', 'text' => 'Zlecero ma skrócić drogę od wiadomości klienta do gotowego zlecenia, bez narzucania ciężkiego CRM-a.'],
                ['title' => 'Dla kogo', 'text' => 'Produkt powstaje dla drukarni, agencji reklamowych, firm od oznakowań, serwisów i małych produkcji na zamówienie.'],
                ['title' => 'Jak rozwijamy produkt', 'text' => 'Najpierw stabilny proces MVP, potem automatyzacje, integracje i rozbudowana analityka na bazie realnych potrzeb pilotażu.'],
            ],
        ],
        'contact' => [
            'title' => 'Porozmawiajmy o Twoim procesie ofertowania.',
            'lead' => 'Opisz krótko, jak dziś obsługujecie zapytania klientów. Odpowiemy z informacją, czy pilot Zlecero pasuje do Waszego przypadku.',
            'form' => [
                'name' => 'Imię i nazwisko',
                'company' => 'Firma',
                'email' => 'Adres e-mail',
                'phone' => 'Telefon',
                'subject' => 'Temat',
                'message' => 'Wiadomość',
                'submit' => 'Wyślij zgłoszenie',
            ],
        ],
    ],
];
