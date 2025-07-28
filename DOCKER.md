# 🐳 Docker - AppDaf

Cette documentation explique comment utiliser Docker avec l'application AppDaf.

## 📋 Prérequis

- Docker installé sur votre système
- Docker Compose installé
- PostgreSQL installé et configuré (sur l'hôte ou serveur externe)

## 🚀 Déploiement rapide

### Développement

```bash
# 1. Configurer les variables d'environnement
cp .env.docker .env
# Éditez .env avec vos paramètres PostgreSQL

# 2. Déployer avec le script
./deploy.sh dev

# Ou manuellement
docker-compose up -d
```

### Production

```bash
# 1. Configurer les variables d'environnement
cp .env.docker .env
# Éditez .env avec vos paramètres PostgreSQL de production

# 2. Déployer avec le script
./deploy.sh prod

# Ou manuellement
docker-compose -f docker-compose.prod.yml up -d
```

## 🔧 Configuration de la base de données

### Connexion depuis Docker vers PostgreSQL local

Dans votre fichier `.env` :
```env
DB_HOST=host.docker.internal
```

### Connexion vers PostgreSQL externe

Dans votre fichier `.env` :
```env
DB_HOST=votre-serveur-postgres.com
DB_PORT=5432
DB_NAME=appdaf
DB_USER=votre_utilisateur
DB_PASS=votre_mot_de_passe
```

## 📝 Commandes Docker utiles

### Construction de l'image
```bash
docker build -t appdaf:latest .
```

### Démarrage des services
```bash
# Développement
docker-compose up -d

# Production
docker-compose -f docker-compose.prod.yml up -d
```

### Arrêt des services
```bash
# Développement
docker-compose down

# Production
docker-compose -f docker-compose.prod.yml down
```

### Voir les logs
```bash
docker-compose logs -f app
```

### Accéder au conteneur
```bash
docker-compose exec app bash
```

### Reconstruire l'image
```bash
docker-compose build --no-cache
```

## 🌐 Accès à l'application

- **Développement** : http://localhost:8080
- **Production** : http://localhost

### Test de l'API
```bash
curl "http://localhost:8080/api/cni?nci=1895200000231"
```

## 📂 Structure Docker

```
├── Dockerfile                 # Image de l'application
├── docker-compose.yml         # Configuration développement
├── docker-compose.prod.yml    # Configuration production
├── .dockerignore              # Fichiers à ignorer
├── deploy.sh                  # Script de déploiement
├── .env.docker               # Template de configuration
└── docker/
    └── apache.conf           # Configuration Apache
```

## 🔒 Sécurité

### En production

1. **Variables d'environnement** : Utilisez des variables d'environnement sécurisées
2. **Volumes** : Les volumes de code source ne sont pas montés en production
3. **Réseau** : Configurez correctement les règles de pare-feu
4. **HTTPS** : Utilisez un reverse proxy (nginx, traefik) pour HTTPS

### Configuration réseau sécurisée

```yaml
# Exemple avec nginx reverse proxy
version: '3.8'
services:
  app:
    build: .
    expose:
      - "80"
    networks:
      - internal
  
  nginx:
    image: nginx:alpine
    ports:
      - "443:443"
    networks:
      - internal
      
networks:
  internal:
    driver: bridge
```

## 🐛 Dépannage

### Problème de connexion à PostgreSQL

1. Vérifiez que PostgreSQL accepte les connexions externes
2. Vérifiez les paramètres dans `.env`
3. Testez la connexion depuis le conteneur :

```bash
docker-compose exec app php database/test-connection.php
```

### Problème de permissions

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
```

### Problème de construction

```bash
docker-compose build --no-cache
docker system prune -f
```

## 📊 Monitoring

### Logs en temps réel
```bash
docker-compose logs -f app
```

### Statistiques des conteneurs
```bash
docker stats
```

### Santé de l'application
```bash
curl http://localhost:8080/api/cni?nci=1895200000231
```
