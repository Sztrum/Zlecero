<?php

declare(strict_types=1);

$frontendUrl = getenv('FRONTEND_URL');

return [
    'url' => is_string($frontendUrl) && $frontendUrl !== '' ? $frontendUrl : config('app.url'),
];
