<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Bestätigen Sie Ihre E-Mail-Adresse',
        'html' => [
            'title' => 'Willkommen :userName, in unserem System!',
            'paragraph-1' => 'Um den Registrierungsprozess abzuschließen, müssen Sie Ihre E-Mail-Adresse bestätigen. Klicken Sie auf die Schaltfläche unten, um dies zu tun.',
            'paragraph-2' => 'Wenn Sie sich nicht in unserem System registriert haben, ignorieren Sie diese E-Mail.',
            'paragraph-3' => 'Vielen Dank, dass Sie sich uns angeschlossen haben!',
            'button-text' => 'Hier klicken',
        ],
    ],
    'reset-password' => [
        'subject' => 'Passwort zurücksetzen',
        'html' => [
            'title' => 'Hallo :userName!',
            'paragraph-1' => 'Sie erhalten diese E-Mail, weil wir eine Anfrage zum Zurücksetzen Ihres Passworts erhalten haben.',
            'paragraph-2' => 'Klicken Sie auf die Schaltfläche unten, um Ihr Passwort zurückzusetzen.',
            'paragraph-3' => 'Wenn Sie kein Zurücksetzen des Passworts angefordert haben, ignorieren Sie diese E-Mail.',
            'button-text' => 'Passwort zurücksetzen',
        ],
    ],
];
