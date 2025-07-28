<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

if (!getenv('DB_HOST') && file_exists(dirname(__DIR__,2) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
    $dotenv->load();
}

return [
    'driver'   => getenv('DB_DRIVER') ?: ($_ENV['DB_DRIVER'] ?? 'postgres'),
    'host'     => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost'),
    'port'     => getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '5432'),
    'dbname'   => getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'appdaf'),
    'charset'  => getenv('DB_CHARSET') ?: ($_ENV['DB_CHARSET'] ?? 'utf8'),
    'username' => getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'postgres'),
    'password' => getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? ''),
];
