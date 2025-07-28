<?php

/**
 * Seeder: Données initiales pour la table citoyen
 * Date: 2025-07-28
 */

class CitoyenSeeder
{
    public function run($pdo)
    {
        // Données initiales
        $citoyens = [
            [
                'nci' => '1895200000231',
                'prenom' => 'Khouss',
                'nom' => 'Ngom',
                'date_naissance' => '1989-05-20',
                'adresse' => 'Dakar',
                'photo' => null
            ],
            // Vous pouvez ajouter d'autres citoyens ici
        ];

        $sql = "
            INSERT INTO citoyen (nci, prenom, nom, date_naissance, adresse, photo) 
            VALUES (:nci, :prenom, :nom, :date_naissance, :adresse, :photo)
            ON CONFLICT (nci) DO NOTHING
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($citoyens as $citoyen) {
            try {
                $stmt->execute([
                    'nci' => $citoyen['nci'],
                    'prenom' => $citoyen['prenom'],
                    'nom' => $citoyen['nom'],
                    'date_naissance' => $citoyen['date_naissance'],
                    'adresse' => $citoyen['adresse'],
                    'photo' => $citoyen['photo']
                ]);
                echo "✅ Citoyen {$citoyen['prenom']} {$citoyen['nom']} ajouté.\n";
            } catch (PDOException $e) {
                echo "❌ Erreur lors de l'ajout de {$citoyen['prenom']} {$citoyen['nom']}: " . $e->getMessage() . "\n";
            }
        }
    }
}
