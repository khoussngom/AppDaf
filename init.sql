-- Script SQL pour créer la table citoyen et insérer les données de test

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
);

-- Insérer les données de test
INSERT INTO citoyen (nci, prenom, nom, date_naissance, adresse, photo) 
VALUES ('1895200000231', 'Khouss', 'Ngom', '1989-05-20', 'Dakar', null)
ON CONFLICT (nci) DO NOTHING;

-- Vérifier l'insertion
SELECT * FROM citoyen WHERE nci = '1895200000231';
