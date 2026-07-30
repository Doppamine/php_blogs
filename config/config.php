<?php

declare(strict_types=1);
return [
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? 'mysql',
        'port' => (int)($_ENV['DB_PORT'] ?? 3306),
        'name' => $_ENV['DB_NAME'] ?? 'blog',
        'user' => $_ENV['DB_USER'] ?? 'blog',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
    ],
    'articles_per_page' => 9,
    'similar_articles' => 3,
    'latest_per_category' => 3,
    'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'local',
];