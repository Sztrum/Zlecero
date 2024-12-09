<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Verifica tu dirección de correo electrónico',
        'html' => [
            'title' => '¡Bienvenido :userName, a nuestro sistema!',
            'paragraph-1' => 'Para completar el proceso de registro, debes verificar tu dirección de correo electrónico. Haz clic en el botón de abajo para hacerlo.',
            'paragraph-2' => 'Si no te registraste en nuestro sistema, ignora este correo electrónico.',
            'paragraph-3' => '¡Gracias por unirte a nosotros!',
            'button-text' => 'Haz clic aquí',
        ],
    ],
    'reset-password' => [
        'subject' => 'Restablecer contraseña',
        'html' => [
            'title' => '¡Hola :userName!',
            'paragraph-1' => 'Estás recibiendo este correo electrónico porque recibimos una solicitud para restablecer la contraseña de tu cuenta.',
            'paragraph-2' => 'Haz clic en el botón de abajo para restablecer tu contraseña.',
            'paragraph-3' => 'Si no solicitaste restablecer tu contraseña, ignora este correo electrónico.',
            'button-text' => 'Restablecer contraseña',
        ],
    ],
];
