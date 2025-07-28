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
        if ($this->config['driver'] === 'postgres' || $this->config['driver'] === 'pgsql') {
        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['dbname']}";
        } else {
            $dsn = "{$this->config['driver']}:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
        }

        try {
            $pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
}
