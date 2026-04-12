<?php

declare(strict_types=1);

$config = [
    'host' => $_ENV['DB_HOST'] ?? null,
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'dbname' => $_ENV['DB_NAME'] ?? null,
    'username' => $_ENV['DB_USER'] ?? null,
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',
];

if (!$config['host'] || !$config['dbname'] || !$config['username']) {
    die('Error: configuración de base de datos incompleta en .env');
}

return $config;