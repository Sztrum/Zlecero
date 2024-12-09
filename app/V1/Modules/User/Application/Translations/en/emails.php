<?php

declare(strict_types=1);

return [
    'verify-email' => [
        'subject' => 'Verify Your Email Address',
        'html' => [
            'title' => 'Welcome :userName, to our system!',
            'paragraph-1' => 'To complete the registration process, you need to verify your email address. Click the button below to do so.',
            'paragraph-2' => 'If you did not register in our system, please ignore this email.',
            'paragraph-3' => 'Thank you for joining us!',
            'button-text' => 'Click here',
        ],
    ],
    'reset-password' => [
        'subject' => 'Reset Password',
        'html' => [
            'title' => 'Hello :userName!',
            'paragraph-1' => 'You are receiving this email because we received a request to reset the password for your account.',
            'paragraph-2' => 'Click the button below to reset your password.',
            'paragraph-3' => 'If you did not request a password reset, please ignore this email.',
            'button-text' => 'Reset Password',
        ],
    ],
];
