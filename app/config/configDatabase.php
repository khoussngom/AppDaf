<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();

return [
    'driver'   => $_ENV['DB_DRIVER'] ?? 'postgres',
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname'   => $_ENV['DB_NAME'] ?? 'appdaf',
    'charset'  => $_ENV['DB_CHARSET'] ?? 'utf8',
    'username' => $_ENV['DB_USER'] ?? 'postgres',
    'password' => $_ENV['DB_PASS'] ?? '',
];
