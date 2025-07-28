<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

echo "Début des migrations...\n\n";

try {
    $config = require __DIR__ . '/../app/config/configDatabase.php';
    
    $database = new Database($config);
    $pdo = $database->connectDatabase();
    
    echo "Connexion à la base de données établie.\n\n";
    
    $sql = "
        CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($sql);
    echo " Table des migrations créée.\n";
    
    $migrationsPath = __DIR__ . '/migrations';
    $migrations = glob($migrationsPath . '/*.php');
    sort($migrations);
    
    foreach ($migrations as $migrationFile) {
        $migrationName = basename($migrationFile, '.php');
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
        $stmt->execute([$migrationName]);
        
        if ($stmt->fetchColumn() == 0) {
            echo " Exécution de la migration: $migrationName\n";
            
            require_once $migrationFile;
            
            $content = file_get_contents($migrationFile);
            if (preg_match('/class\s+(\w+)/', $content, $matches)) {
                $className = $matches[1];
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->up($pdo);
                    
                    $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
                    $stmt->execute([$migrationName]);
                    
                    echo "Migration $migrationName terminée.\n\n";
                } else {
                    echo " Classe $className introuvable.\n";
                }
            }
        } else {
            echo " Migration $migrationName déjà exécutée.\n";
        }
    }
    
    echo "Toutes les migrations sont terminées!\n\n";
    
} catch (Exception $e) {
    echo " Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
