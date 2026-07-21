<?php

declare(strict_types=1);

return [
    'meta' => [
        'title' => 'E-Mail-Anfragen in Aufträge verwandeln',
        'description' => 'Zlecero ordnet Nachrichten, Anhänge, Angebote und Bearbeitungsstatus für kleine und mittlere Unternehmen.',
    ],
    'navigation' => [
        ['label' => 'So funktioniert es', 'href' => '#jak-dziala'],
        ['label' => 'Funktionen', 'href' => '#funkcje'],
        ['label' => 'Für wen', 'href' => '#dla-kogo'],
        ['label' => 'Preise', 'href' => '#cennik'],
        ['label' => 'FAQ', 'href' => '#faq'],
    ],
    'navigation_actions' => [
        'login' => 'Einloggen',
        'join' => 'Beitreten',
        'join_pilot' => 'Am Pilot teilnehmen',
        'language_label' => 'Sprachauswahl',
    ],
    'hero' => [
        'badge' => 'Das Pilotprogramm läuft - kostenlos teilnehmen',
        'title_prefix' => 'Verwandle jede E-Mail-Anfrage in einen',
        'title_accent' => 'geordneten Auftrag.',
        'description' => 'Zlecero ordnet Nachrichten, Anhänge, Angebote und Bearbeitungsstatus. Schluss mit Dateisuche, E-Mail-Kopieren und der Frage, wer dem Kunden antworten sollte.',
        'primary_action' => 'Am Pilot teilnehmen',
        'secondary_action' => 'Ablauf ansehen',
        'stats' => ['7 neue', '4 Angebote', '5 Aufträge', '3 Hinweise'],
        'preview_navigation' => ['Dashboard', 'Postfach', 'Anfragen', 'Angebote', 'Aufträge'],
    ],
    'problems' => [
        'title' => 'Sieht die Arbeit in Ihrer Firma so aus?',
        'description' => 'Probleme, die wir von Firmen hören, die Anfragen per E-Mail bearbeiten.',
        'items' => [
            ['icon' => '📧', 'title' => 'Anfragen gehen im Postfach verloren', 'description' => 'Kunden schreiben, aber E-Mails versinken zwischen hunderten anderen Nachrichten.'],
            ['icon' => '💾', 'title' => 'Dateien liegen auf mehreren Computern', 'description' => 'Niemand weiß, wo die aktuelle Projektversion liegt.'],
            ['icon' => '❓', 'title' => 'Wer betreut den Kunden?', 'description' => 'Jeder denkt, jemand anderes habe geantwortet.'],
            ['icon' => '⏰', 'title' => 'Angebote warten ohne Antwort', 'description' => 'Der Kunde erhielt vor einer Woche ein Angebot. Niemand hat nachgefasst.'],
            ['icon' => '🔍', 'title' => 'Status ist schwer zu prüfen', 'description' => 'Der Kunde fragt, und Sie suchen 10 Minuten.'],
            ['icon' => '📋', 'title' => 'Excel statt System', 'description' => 'Tabellen, die niemand laufend aktualisiert.'],
        ],
    ],
    'process' => [
        'title' => 'Wie funktioniert es?',
        'description' => 'Fünf Schritte ersetzen E-Mail, Excel und Ordner.',
        'step_label' => 'SCHRITT :number',
        'steps' => [
            ['number' => '1', 'label' => 'Kunde sendet eine Nachricht', 'icon' => 'mail', 'variant' => 'primary'],
            ['number' => '2', 'label' => 'Zlecero erstellt eine Anfrage', 'icon' => 'file-question', 'variant' => 'accent'],
            ['number' => '3', 'label' => 'Team bereitet Angebot vor', 'icon' => 'file-text', 'variant' => 'info'],
            ['number' => '4', 'label' => 'Kunde akzeptiert online', 'icon' => 'check-circle', 'variant' => 'success'],
            ['number' => '5', 'label' => 'Anfrage wird zum Auftrag', 'icon' => 'briefcase', 'variant' => 'warning'],
        ],
    ],
    'comparison' => [
        'title' => 'Heute vs Zlecero',
        'current' => [
            'title' => 'Heute',
            'items' => [
                'E-Mail, E-Mail, E-Mail...',
                'Excel mit Kundendaten',
                'Anruf: "Wo ist dieses Angebot?"',
                'Ordner mit unbenannten Dateien',
                'Zettel und Notizen',
                'Niemand weiß, wer antwortet',
            ],
        ],
        'zlecero' => [
            'title' => 'Zlecero',
            'items' => [
                'Eine Anfrage, ganze Historie',
                'Kundenpanel mit Online-Angebot',
                'Klarer Status jedes Auftrags',
                'Alle Dateien an einem Ort',
                'Automatische Erinnerungen',
                'Eine verantwortliche Person',
            ],
        ],
    ],
    'pilot' => [
        'badge' => 'Pilotprogramm',
        'title' => 'Gehören Sie zu den ersten Firmen mit Zlecero.',
        'description' => 'Kostenlose Einführung · Kostenlose Testphase · Einfluss auf das Produkt · Vorzugspreis',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Vor- und Nachname', 'placeholder' => 'Jan Kowalski'],
            ['name' => 'company', 'type' => 'text', 'label' => 'Firma', 'placeholder' => 'Ihre Firma GmbH'],
            ['name' => 'email', 'type' => 'email', 'label' => 'E-Mail-Adresse', 'placeholder' => 'jan@firma.pl'],
            ['name' => 'phone', 'type' => 'tel', 'label' => 'Telefon', 'placeholder' => '+48 600 000 000'],
        ],
        'message_label' => 'Wie bearbeiten Sie Kundenanfragen heute?',
        'message_placeholder' => 'E-Mail, Excel, Telefon...',
        'submit' => 'Für den Pilot anmelden',
        'notice' => 'Ohne Verpflichtung. Wir antworten innerhalb von 24 Stunden.',
    ],
    'faq' => [
        'title' => 'Häufige Fragen',
        'items' => [
            ['question' => 'Muss ich mein E-Mail-Postfach wechseln?', 'answer' => 'Nein. Zlecero funktioniert mit Ihrem aktuellen Postfach: Gmail, Outlook oder jedem IMAP-Postfach. E-Mails kommen weiter bei Ihnen an, Zlecero ordnet sie.'],
            ['question' => 'Funktioniert es mit Gmail und Microsoft 365?', 'answer' => 'Ja. Zlecero unterstützt Integrationen mit beiden Plattformen. Die Einrichtung dauert wenige Minuten.'],
            ['question' => 'Kann ich mehrere Mitarbeiter hinzufügen?', 'answer' => 'Ja. Sie können Teammitglieder einladen und Rollen wie Eigentümer, Administrator, Mitarbeiter oder Nur-Lesen vergeben.'],
            ['question' => 'Muss der Kunde ein Konto erstellen?', 'answer' => 'Nein. Der Kunde erhält einen Link zum Angebot und kann es ohne Registrierung prüfen und akzeptieren.'],
            ['question' => 'Ersetzt Zlecero ein Rechnungsprogramm?', 'answer' => 'Nein. Zlecero ordnet Anfragen, Angebote und Aufträge. Rechnungen stellen Sie in Ihrer bisherigen Software aus.'],
            ['question' => 'Was kostet das System nach dem Pilot?', 'answer' => 'Die Preise werden anhand der Ergebnisse des Pilotprogramms festgelegt. Teilnehmer erhalten bevorzugte Konditionen.'],
        ],
    ],
    'footer' => [
        'description' => 'System zur Verwaltung von Anfragen, Angeboten und Aufträgen für kleine und mittlere Unternehmen.',
        'copyright' => '© 2026 Zlecero. Alle Rechte vorbehalten.',
        'columns' => [
            ['title' => 'Produkt', 'links' => [
                ['label' => 'So funktioniert es', 'href' => '#jak-dziala'],
                ['label' => 'Funktionen', 'href' => '#funkcje'],
                ['label' => 'Preise', 'href' => '#cennik'],
                ['label' => 'Pilot', 'href' => '#cennik'],
            ]],
            ['title' => 'Firma', 'links' => [
                ['label' => 'Über uns', 'href' => '#top'],
                ['label' => 'Kontakt', 'href' => '#cennik'],
                ['label' => 'Blog', 'href' => '#top'],
            ]],
            ['title' => 'Rechtliches', 'links' => [
                ['label' => 'AGB', 'href' => '#top'],
                ['label' => 'Datenschutz', 'href' => '#top'],
                ['label' => 'Daten und Sicherheit', 'href' => '#top'],
            ]],
        ],
    ],
];
