<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Interface\DatabaseInterface;

class Database implements DatabaseInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connectDatabase()
    {
        // Log de la configuration pour debug
        error_log("Configuration DB: " . json_encode([
            'driver' => $this->config['driver'],
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'dbname' => $this->config['dbname'],
            'username' => $this->config['username']
        ]));

        if ($this->config['driver'] === 'postgres' || $this->config['driver'] === 'pgsql') {
        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['dbname']}";
        } else {
            $dsn = "{$this->config['driver']}:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
        }

        error_log("DSN: $dsn");

        try {
            $pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 30, // Timeout de 30 secondes
            ]);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Erreur PDO détaillée: " . $e->getMessage());
            throw new PDOException("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
}
