<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger .env en local si disponible
if (file_exists(dirname(__DIR__,2) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
    $dotenv->load();
}

// Fonction pour récupérer une variable d'environnement
function getEnvVar($key, $default = null) {
    return getenv($key) ?: ($_ENV[$key] ?? ($_SERVER[$key] ?? $default));
}

// Railway utilise DATABASE_URL par défaut
$database_url = getEnvVar('DATABASE_URL');

if ($database_url) {
    // Analyser DATABASE_URL (format: postgresql://user:password@host:port/database)
    $parsed = parse_url($database_url);
    
    return [
        'driver'   => 'pgsql',
        'host'     => $parsed['host'],
        'port'     => $parsed['port'] ?? '5432',
        'dbname'   => ltrim($parsed['path'], '/'),
        'charset'  => 'utf8',
        'username' => $parsed['user'],
        'password' => $parsed['pass'],
    ];
} else {
    // Configuration manuelle via variables d'environnement
    return [
        'driver'   => getEnvVar('DB_DRIVER', 'postgres'),
        'host'     => getEnvVar('DB_HOST', 'localhost'),
        'port'     => getEnvVar('DB_PORT', '5432'),
        'dbname'   => getEnvVar('DB_NAME', 'appdaf'),
        'charset'  => getEnvVar('DB_CHARSET', 'utf8'),
        'username' => getEnvVar('DB_USER', 'postgres'),
        'password' => getEnvVar('DB_PASS', ''),
    ];
}
