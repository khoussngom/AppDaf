<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;


class MigrationManager
{
    private $pdo;
    private $migrationsPath;
    private $seedersPath;

    public function __construct()
    {
        $config = require __DIR__ . '/../app/config/configDatabase.php';
        $database = new Database($config);
        $this->pdo = $database->connectDatabase();
        $this->migrationsPath = __DIR__ . '/migrations';
        $this->seedersPath = __DIR__ . '/seeders';
        
        $this->createMigrationsTable();
    }

    private function createMigrationsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $this->pdo->exec($sql);
    }

    public function migrate()
    {
        echo "Début des migrations...\n\n";
        
        $migrations = glob($this->migrationsPath . '/*.php');
        sort($migrations);

        foreach ($migrations as $migrationFile) {
            $migrationName = basename($migrationFile, '.php');
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
            $stmt->execute([$migrationName]);
            
            if ($stmt->fetchColumn() == 0) {
                echo " Exécution de la migration: $migrationName\n";
                
                require_once $migrationFile;
                $className = $this->getMigrationClassName($migrationFile);
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->up($this->pdo);
                    
                    $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
                    $stmt->execute([$migrationName]);
                    
                    echo "Migration $migrationName terminée.\n\n";
                } else {
                    echo " Classe $className introuvable dans $migrationFile\n";
                }
            } else {
                echo " Migration $migrationName déjà exécutée.\n";
            }
        }
        
        echo " Toutes les migrations sont terminées!\n\n";
    }

    public function seed()
    {
        echo " Début du seeding...\n\n";
        
        $seeders = glob($this->seedersPath . '/*.php');
        
        foreach ($seeders as $seederFile) {
            $seederName = basename($seederFile, '.php');
            echo " Exécution du seeder: $seederName\n";
            
            require_once $seederFile;
            
            if (class_exists($seederName)) {
                $seeder = new $seederName();
                $seeder->run($this->pdo);
                echo "Seeder $seederName terminé.\n\n";
            } else {
                echo " Classe $seederName introuvable dans $seederFile\n";
            }
        }
        
        echo "Seeding terminé!\n";
    }

    private function getMigrationClassName($file)
    {
        $content = file_get_contents($file);
        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function rollback()
    {
        echo " Rollback des migrations...\n";
        
        $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 1");
        $lastMigration = $stmt->fetchColumn();
        
        if ($lastMigration) {
            $migrationFile = $this->migrationsPath . '/' . $lastMigration . '.php';
            
            if (file_exists($migrationFile)) {
                require_once $migrationFile;
                $className = $this->getMigrationClassName($migrationFile);
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->down($this->pdo);
                    
                    $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE migration = ?");
                    $stmt->execute([$lastMigration]);
                    
                    echo "Rollback de $lastMigration terminé.\n";
                }
            }
        } else {
            echo " Aucune migration à rollback.\n";
        }
    }
}

if (php_sapi_name() === 'cli') {
    $manager = new MigrationManager();
    
    $command = $argv[1] ?? 'help';
    
    switch ($command) {
        case 'migrate':
            $manager->migrate();
            break;
        case 'seed':
            $manager->seed();
            break;
        case 'fresh':
            echo "Migration fresh (rollback + migrate + seed)...\n\n";
            $manager->rollback();
            $manager->migrate();
            $manager->seed();
            break;
        case 'rollback':
            $manager->rollback();
            break;
        default:
            echo " Commandes disponibles:\n";
            echo "  php database/migrate.php migrate   - Exécuter les migrations\n";
            echo "  php database/migrate.php seed      - Exécuter les seeders\n";
            echo "  php database/migrate.php fresh     - Rollback + migrate + seed\n";
            echo "  php database/migrate.php rollback  - Rollback de la dernière migration\n";
            break;
    }
}
