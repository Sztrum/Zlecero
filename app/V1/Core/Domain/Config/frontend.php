<?php

declare(strict_types=1);

$readStringEnv = static function (string $key): ?string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    return is_string($value) && $value !== '' ? $value : null;
};

$frontendUrl = $readStringEnv('FRONTEND_URL');
$frontendAppUrl = $readStringEnv('FRONTEND_APP_URL');
$appUrl = config('app.url');

return [
    'url' => $frontendUrl !== null
        ? $frontendUrl
        : (
            $frontendAppUrl !== null
                ? $frontendAppUrl
                : (is_string($appUrl) && $appUrl !== '' ? $appUrl : 'http://localhost')
        ),
];
