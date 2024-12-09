<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Vérifiez votre adresse e-mail',
        'html' => [
            'title' => 'Bienvenue :userName, dans notre système !',
            'paragraph-1' => 'Pour finaliser le processus d\'inscription, vous devez vérifier votre adresse e-mail. Cliquez sur le bouton ci-dessous pour le faire.',
            'paragraph-2' => 'Si vous ne vous êtes pas inscrit dans notre système, ignorez cet e-mail.',
            'paragraph-3' => 'Merci de nous avoir rejoints !',
            'button-text' => 'Cliquez ici',
        ],
    ],
    'reset-password' => [
        'subject' => 'Réinitialisez votre mot de passe',
        'html' => [
            'title' => 'Bonjour :userName !',
            'paragraph-1' => 'Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.',
            'paragraph-2' => 'Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe.',
            'paragraph-3' => 'Si vous n\'avez pas demandé à réinitialiser votre mot de passe, ignorez cet e-mail.',
            'button-text' => 'Réinitialiser le mot de passe',
        ],
    ],
];
