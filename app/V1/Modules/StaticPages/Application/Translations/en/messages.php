<?php

declare(strict_types=1);

$pl = require __DIR__.'/../pl/messages.php';

if (! is_array($pl)) {
    return [];
}

data_set($pl, 'meta.landing.title', 'Zlecero - email inquiries, quotes and orders in one place');
data_set($pl, 'meta.landing.description', 'Zlecero organizes customer emails into inquiries, quotes and accepted orders for small service teams.');
data_set($pl, 'pages.landing.hero_title', 'Turn every customer email into an organized order.');
data_set($pl, 'pages.landing.hero_text', 'Zlecero keeps messages, files, quotes and order statuses together so the team always knows what needs a response.');

return $pl;
