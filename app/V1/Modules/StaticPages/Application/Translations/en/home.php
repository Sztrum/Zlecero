<?php

declare(strict_types=1);

return [
    'meta' => [
        'title' => 'Turn email inquiries into orders',
        'description' => 'Zlecero organizes messages, attachments, offers, and delivery statuses for small and medium businesses.',
    ],
    'navigation' => [
        ['label' => 'How it works', 'href' => '#jak-dziala'],
        ['label' => 'Features', 'href' => '#funkcje'],
        ['label' => 'For whom', 'href' => '#dla-kogo'],
        ['label' => 'Pricing', 'href' => '#cennik'],
        ['label' => 'FAQ', 'href' => '#faq'],
    ],
    'navigation_actions' => [
        'login' => 'Log in',
        'join' => 'Join',
        'join_pilot' => 'Join the pilot',
        'language_label' => 'Language selection',
    ],
    'hero' => [
        'badge' => 'Pilot program is open - join for free',
        'title_prefix' => 'Turn every email inquiry into an',
        'title_accent' => 'organized order.',
        'description' => 'Zlecero organizes messages, attachments, offers, and delivery statuses. Stop searching for files, copying emails, and wondering who should reply to the customer.',
        'primary_action' => 'Join the pilot',
        'secondary_action' => 'See how it works',
        'stats' => ['7 new', '4 offers', '5 orders', '3 notes'],
        'preview_navigation' => ['Dashboard', 'Inbox', 'Inquiries', 'Offers', 'Orders'],
    ],
    'problems' => [
        'title' => 'Does work in your company look like this?',
        'description' => 'Problems we hear from companies handling inquiries by email.',
        'items' => [
            ['icon' => '📧', 'title' => 'Inquiries get lost in the inbox', 'description' => 'Customers write, but emails sink among hundreds of other messages.'],
            ['icon' => '💾', 'title' => 'Files are spread across computers', 'description' => 'Nobody knows where the latest project version is.'],
            ['icon' => '❓', 'title' => 'Who owns the customer?', 'description' => 'Everyone thinks someone else has replied.'],
            ['icon' => '⏰', 'title' => 'Offers wait without follow-up', 'description' => 'The customer received an offer a week ago. Nobody reminded them.'],
            ['icon' => '🔍', 'title' => 'Status is hard to check', 'description' => 'The customer asks, and you search for 10 minutes.'],
            ['icon' => '📋', 'title' => 'Excel instead of a system', 'description' => 'Spreadsheets that nobody updates in real time.'],
        ],
    ],
    'process' => [
        'title' => 'How does it work?',
        'description' => 'Five steps that replace email, Excel, and binders.',
        'step_label' => 'STEP :number',
        'steps' => [
            ['number' => '1', 'label' => 'Customer sends a message', 'icon' => 'mail', 'variant' => 'primary'],
            ['number' => '2', 'label' => 'Zlecero creates an inquiry', 'icon' => 'file-question', 'variant' => 'accent'],
            ['number' => '3', 'label' => 'Team prepares an offer', 'icon' => 'file-text', 'variant' => 'info'],
            ['number' => '4', 'label' => 'Customer accepts online', 'icon' => 'check-circle', 'variant' => 'success'],
            ['number' => '5', 'label' => 'Inquiry becomes an order', 'icon' => 'briefcase', 'variant' => 'warning'],
        ],
    ],
    'comparison' => [
        'title' => 'Today vs Zlecero',
        'current' => [
            'title' => 'Today',
            'items' => [
                'Email, email, email...',
                'Excel with customer data',
                'Phone call: "Where is that offer?"',
                'Folders with unnamed files',
                'Paper notes',
                'Nobody knows who should reply',
            ],
        ],
        'zlecero' => [
            'title' => 'Zlecero',
            'items' => [
                'One inquiry, complete history',
                'Customer panel with online offer',
                'Clear status for every order',
                'All files in one place',
                'Automatic reminders',
                'One responsible owner',
            ],
        ],
    ],
    'pilot' => [
        'badge' => 'Pilot program',
        'title' => 'Join the first companies using Zlecero.',
        'description' => 'Free implementation · Free trial period · Product influence · Preferred pricing',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Full name', 'placeholder' => 'John Smith'],
            ['name' => 'company', 'type' => 'text', 'label' => 'Company', 'placeholder' => 'Your Company Ltd.'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email address', 'placeholder' => 'john@company.com'],
            ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone', 'placeholder' => '+48 600 000 000'],
        ],
        'message_label' => 'How do you handle customer inquiries today?',
        'message_placeholder' => 'Email, Excel, phone...',
        'submit' => 'Sign me up for the pilot',
        'notice' => 'No obligations. We will reply within 24 hours.',
    ],
    'faq' => [
        'title' => 'Frequently asked questions',
        'items' => [
            ['question' => 'Do I need to change my email inbox?', 'answer' => 'No. Zlecero works with your current inbox: Gmail, Outlook, or any IMAP mailbox. Emails still reach you, and Zlecero organizes them.'],
            ['question' => 'Does it work with Gmail and Microsoft 365?', 'answer' => 'Yes. Zlecero supports integrations with both platforms. Setup takes a few minutes.'],
            ['question' => 'Can I add several employees?', 'answer' => 'Yes. You can invite team members and assign owner, admin, employee, or read-only roles.'],
            ['question' => 'Does the customer need an account?', 'answer' => 'No. The customer receives an offer link and can review and accept it without registration.'],
            ['question' => 'Does Zlecero replace invoicing software?', 'answer' => 'No. Zlecero organizes inquiries, offers, and orders. You issue invoices in your current software.'],
            ['question' => 'How much will it cost after the pilot?', 'answer' => 'Pricing will be set based on pilot results. Pilot participants will receive preferred terms.'],
        ],
    ],
    'footer' => [
        'description' => 'A system for managing inquiries, offers, and orders for small and medium businesses.',
        'copyright' => '© 2026 Zlecero. All rights reserved.',
        'columns' => [
            ['title' => 'Product', 'links' => [
                ['label' => 'How it works', 'href' => '#jak-dziala'],
                ['label' => 'Features', 'href' => '#funkcje'],
                ['label' => 'Pricing', 'href' => '#cennik'],
                ['label' => 'Pilot', 'href' => '#cennik'],
            ]],
            ['title' => 'Company', 'links' => [
                ['label' => 'About us', 'href' => '#top'],
                ['label' => 'Contact', 'href' => '#cennik'],
                ['label' => 'Blog', 'href' => '#top'],
            ]],
            ['title' => 'Legal', 'links' => [
                ['label' => 'Terms', 'href' => '#top'],
                ['label' => 'Privacy policy', 'href' => '#top'],
                ['label' => 'Data and security', 'href' => '#top'],
            ]],
        ],
    ],
];
