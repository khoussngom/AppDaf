<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger le fichier .env seulement s'il existe
if (file_exists(dirname(__DIR__,2) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
    $dotenv->load();
}

return [
    'driver'   => $_ENV['DB_DRIVER'] ?? getenv('DB_DRIVER') ?? 'postgres',
    'host'     => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost',
    'port'     => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '5432',
    'dbname'   => $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'appdaf',
    'charset'  => $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?? 'utf8',
    'username' => $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'postgres',
    'password' => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '',
];
