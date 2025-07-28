<?php

class CreateCitoyenTable
{
    public function up($pdo)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS citoyen (
                id SERIAL PRIMARY KEY,
                nci VARCHAR(20) UNIQUE NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                nom VARCHAR(100) NOT NULL,
                date_naissance DATE,
                adresse TEXT,
                photo TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $pdo->exec($sql);
        echo " Table 'citoyen' créée avec succès.\n";
    }

    public function down($pdo)
    {
        $sql = "DROP TABLE IF EXISTS citoyen";
        $pdo->exec($sql);
        echo " Table 'citoyen' supprimée.\n";
    }
}
