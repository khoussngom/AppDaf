<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

echo " Début du seeding...\n\n";

try {
    $config = require __DIR__ . '/../app/config/configDatabase.php';
    
    $database = new Database($config);
    $pdo = $database->connectDatabase();
    
    echo "Connexion à la base de données établie.\n\n";
    
    $seedersPath = __DIR__ . '/seeders';
    $seeders = glob($seedersPath . '/*.php');
    
    foreach ($seeders as $seederFile) {
        $seederName = basename($seederFile, '.php');
        echo "⏳ Exécution du seeder: $seederName\n";
        
        require_once $seederFile;
        
        if (class_exists($seederName)) {
            $seeder = new $seederName();
            $seeder->run($pdo);
            echo "Seeder $seederName terminé.\n\n";
        } else {
            echo " Classe $seederName introuvable.\n";
        }
    }
    
    echo "Seeding terminé!\n";
    
} catch (Exception $e) {
    echo " Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
