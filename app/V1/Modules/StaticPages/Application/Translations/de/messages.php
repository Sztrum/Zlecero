<?php

declare(strict_types=1);

$pl = require __DIR__.'/../pl/messages.php';

if (! is_array($pl)) {
    return [];
}

data_set($pl, 'meta.landing.title', 'Zlecero - E-Mail-Anfragen, Angebote und Aufträge an einem Ort');
data_set($pl, 'meta.landing.description', 'Zlecero organisiert Kunden-E-Mails in Anfragen, Angebote und angenommene Aufträge für kleine Serviceteams.');
data_set($pl, 'pages.landing.hero_title', 'Verwandle jede Kunden-E-Mail in einen geordneten Auftrag.');
data_set($pl, 'pages.landing.hero_text', 'Zlecero hält Nachrichten, Dateien, Angebote und Auftragsstatus zusammen, damit das Team immer den nächsten Schritt kennt.');

return $pl;
