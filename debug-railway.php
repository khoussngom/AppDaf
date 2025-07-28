<?php

echo "=== Debug Railway Configuration ===\n";

// Afficher toutes les variables d'environnement disponibles
echo "Variables d'environnement système:\n";
$env_vars = ['DB_DRIVER', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS'];

foreach ($env_vars as $var) {
    echo "$var:\n";
    echo "  getenv(): " . (getenv($var) ?: 'NON DÉFINI') . "\n";
    echo "  \$_ENV: " . ($_ENV[$var] ?? 'NON DÉFINI') . "\n";
    echo "  \$_SERVER: " . ($_SERVER[$var] ?? 'NON DÉFINI') . "\n";
    echo "\n";
}

// Tester la configuration finale
echo "Configuration finale chargée:\n";
$config = include __DIR__ . '/app/config/configDatabase.php';
print_r($config);

// Railway définit automatiquement des variables pour PostgreSQL
echo "Variables Railway automatiques:\n";
$railway_vars = ['DATABASE_URL', 'PGHOST', 'PGPORT', 'PGDATABASE', 'PGUSER', 'PGPASSWORD'];
foreach ($railway_vars as $var) {
    $value = getenv($var);
    echo "$var: " . ($value ? ($var === 'PGPASSWORD' || $var === 'DATABASE_URL' ? '***DÉFINI***' : $value) : 'NON DÉFINI') . "\n";
}

// Si DATABASE_URL est défini, l'analyser
$database_url = getenv('DATABASE_URL');
if ($database_url) {
    echo "\nAnalyse de DATABASE_URL:\n";
    $parsed = parse_url($database_url);
    echo "Host: " . ($parsed['host'] ?? 'NON DÉFINI') . "\n";
    echo "Port: " . ($parsed['port'] ?? 'NON DÉFINI') . "\n";
    echo "Database: " . (ltrim($parsed['path'] ?? '', '/') ?: 'NON DÉFINI') . "\n";
    echo "User: " . ($parsed['user'] ?? 'NON DÉFINI') . "\n";
    echo "Password: " . (isset($parsed['pass']) ? '***DÉFINI***' : 'NON DÉFINI') . "\n";
}

echo "\nTest de connexion avec la configuration actuelle:\n";
try {
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    echo "DSN: $dsn\n";
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 30,
    ]);
    
    echo "✅ Connexion réussie !\n";
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "Version PostgreSQL: $version\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
}
