# AppDaf - API de Gestion des Citoyens

Une API REST en PHP pour la gestion des informations des citoyens.

## 🏗️ Architecture

```
├── app/                    # Configuration et core de l'application
│   ├── bootstrap.php       # Bootstrapper de l'application
│   ├── Router.php          # Routeur principal
│   ├── config/            # Configuration de l'application
│   └── core/              # Classes core (Container, Database)
├── database/              # Migrations et seeders
│   ├── migrations/        # Scripts de migration
│   └── seeders/          # Scripts de données initiales
├── public/               # Point d'entrée public
│   └── index.php         # Fichier d'entrée principal
├── router/               # Configuration des routes
├── src/                  # Code source de l'application
│   ├── controller/       # Contrôleurs
│   ├── entity/          # Entités/Models
│   ├── interface/       # Interfaces
│   ├── repository/      # Couche d'accès aux données
│   └── services/        # Services métier
└── vendor/              # Dépendances Composer
```

## 🚀 Installation

### Option 1: Installation traditionnelle

#### Prérequis
- PHP 8.1+
- PostgreSQL
- Composer

#### Installation
1. Cloner le projet
```bash
git clone [URL_DU_REPO]
cd AppDaf
```

2. Installer les dépendances
```bash
composer install
```

3. Configurer la base de données
```bash
# Créer le fichier .env avec vos paramètres
cp .env.example .env

# Éditer .env avec vos paramètres PostgreSQL
DB_DRIVER=postgres
DB_HOST=localhost
DB_NAME=appdaf
DB_USER=postgres
DB_PASS=votre_mot_de_passe
DB_CHARSET=utf8
```

4. Initialiser la base de données
```bash
# Exécuter le script SQL
psql -h localhost -U postgres -d appdaf -f init.sql

# Ou utiliser les migrations
php database/run-migrations.php
php database/run-seeders.php
```

### Option 2: Installation avec Docker 🐳

#### Prérequis
- Docker et Docker Compose installés
- PostgreSQL configuré (local ou externe)

#### Installation rapide
```bash
# 1. Configurer l'environnement
cp .env.docker .env
# Éditez .env avec vos paramètres PostgreSQL

# 2. Déployer l'application
./deploy.sh dev
```

Pour plus de détails sur Docker, consultez [DOCKER.md](DOCKER.md).

## 🖥️ Utilisation

### Serveur traditionnel
```bash
php -S localhost:8000 -t public
```

### Avec Docker
```bash
# Développement (port 8080)
docker-compose up -d

# Production (port 80)
docker-compose -f docker-compose.prod.yml up -d
```

### Tester l'API
```bash
# Serveur traditionnel
curl "http://localhost:8000/api/cni?nci=1895200000231"

# Docker (développement)
curl "http://localhost:8080/api/cni?nci=1895200000231"

# Docker (production)
curl "http://localhost/api/cni?nci=1895200000231"
```

## 📡 Endpoints

### GET /api/cni
Rechercher un citoyen par son NCI (Numéro de Carte d'Identité)

**Paramètres:**
- `nci` (string, required): Numéro de carte d'identité

**Réponse:**
```json
{
  "id": 1,
  "nci": "1895200000231",
  "prenom": "Khouss",
  "nom": "Ngom",
  "date_naissance": "1989-05-20",
  "adresse": "Dakar",
  "photo": null
}
```

**Erreurs:**
- `400`: Requête invalide ou NCI manquant
- `404`: Citoyen non trouvé

## 🗄️ Base de données

### Table citoyen
```sql
CREATE TABLE citoyen (
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
```

## 🧪 Tests

Les données de test incluent :
- **NCI**: 1895200000231
- **Prénom**: Khouss
- **Nom**: Ngom
- **Date de naissance**: 1989-05-20
- **Adresse**: Dakar

## 🛠️ Technologies

- **PHP 8.1+**: Langage principal
- **PostgreSQL**: Base de données
- **Composer**: Gestionnaire de dépendances
- **PSR-4**: Autoloading standard

## 📝 License

Ce projet est sous licence [LICENSE_TYPE].
