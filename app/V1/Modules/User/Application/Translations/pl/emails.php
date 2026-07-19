<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Potwierdź swój adres e-mail',
        'html' => [
            'title' => 'Witaj :userName, w naszym systemie!',
            'paragraph-1' => 'Aby zakończyć proces rejestracji, musisz zweryfikować swój adres e-mail. Kliknij przycisk poniżej, aby to zrobić.',
            'paragraph-2' => 'Jeśli nie rejestrowałeś się w naszym systemie, zignoruj ten e-mail.',
            'paragraph-3' => 'Dziękujemy, że dołączyłeś do nas!',
            'button-text' => 'Kliknij tutaj',
        ]
    ],
    'reset-password' => [
        'subject' => 'Zresetuj hasło',
        'html' => [
            'title' => 'Witaj :userName!',
            'paragraph-1' => 'Otrzymujesz ten e-mail, ponieważ otrzymaliśmy prośbę o zresetowanie hasła dla Twojego konta.',
            'paragraph-2' => 'Kliknij przycisk poniżej, aby zresetować hasło.',
            'paragraph-3' => 'Jeśli nie prosiłeś o zresetowanie hasła, zignoruj ten e-mail.',
            'button-text' => 'Zresetuj hasło',
        ],
    ],
];
