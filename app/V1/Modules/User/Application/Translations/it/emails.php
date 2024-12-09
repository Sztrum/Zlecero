<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Verifica il tuo indirizzo email',
        'html' => [
            'title' => 'Benvenuto :userName, nel nostro sistema!',
            'paragraph-1' => 'Per completare il processo di registrazione, devi verificare il tuo indirizzo email. Clicca sul pulsante qui sotto per farlo.',
            'paragraph-2' => 'Se non ti sei registrato nel nostro sistema, ignora questa email.',
            'paragraph-3' => 'Grazie per esserti unito a noi!',
            'button-text' => 'Clicca qui',
        ],
    ],
    'reset-password' => [
        'subject' => 'Reimposta la password',
        'html' => [
            'title' => 'Ciao :userName!',
            'paragraph-1' => 'Stai ricevendo questa email perché abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.',
            'paragraph-2' => 'Clicca sul pulsante qui sotto per reimpostare la tua password.',
            'paragraph-3' => 'Se non hai richiesto la reimpostazione della password, ignora questa email.',
            'button-text' => 'Reimposta password',
        ],
    ],
];
