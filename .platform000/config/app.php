<?php
return [
    'name'    => $_ENV['APP_NAME'] ?? 'Federation',
    'env'     => $_ENV['APP_ENV']  ?? 'local',
    'debug'   => (bool)($_ENV['APP_DEBUG'] ?? false),
    'url'     => $_ENV['APP_URL']  ?? 'http://localhost',
    'session' => [
        'name'     => $_ENV['SESSION_NAME']     ?? 'fsess',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
    ],
    'bootstrap_code' => $_ENV['BOOTSTRAP_CODE'] ?? '',
    'locales'        => ['uk', 'ru', 'en'],
    'default_locale' => 'uk',
];