<?php

return [
    'default' => 'default',
    'accounts' => [
        'default' => [
            'host' => env('IMAP_HOST', 'mail.izitravel.id'),
            'port' => (int) env('IMAP_PORT', 993),
            'protocol' => 'imap',
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => env('IMAP_USERNAME', 'web@izitravel.id'),
            'password' => env('IMAP_PASSWORD', env('MAIL_PASSWORD')),
            'timeout' => 15,
        ],
    ],
    'options' => [
        'fetch' => \Webklex\PHPIMAP\IMAP::FT_PEEK,
        'sequence' => \Webklex\PHPIMAP\IMAP::ST_UID,
        'fetch_order' => 'asc',
    ],
];
