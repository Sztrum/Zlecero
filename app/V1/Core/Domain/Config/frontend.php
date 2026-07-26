<?php

declare(strict_types=1);

$frontendUrl = getenv('FRONTEND_URL');
$frontendAppUrl = getenv('FRONTEND_APP_URL');

return [
    'url' => is_string($frontendUrl) && $frontendUrl !== ''
        ? $frontendUrl
        : (is_string($frontendAppUrl) && $frontendAppUrl !== '' ? $frontendAppUrl : config('app.url')),
];
