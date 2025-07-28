# 🐳 Docker avec Railway PostgreSQL - AppDaf

Cette application PHP est dockerisée et utilise une base de données PostgreSQL hébergée sur Railway.

## 🔧 Configuration actuelle

### Base de données (Railway)
- **Provider**: Railway PostgreSQL
- **Host**: interchange.proxy.rlwy.net
- **Port**: 47627
- **Database**: railway
- **User**: postgres

### Architecture
- **Application**: Conteneur PHP 8.2 + Apache
- **Base de données**: PostgreSQL sur Railway (externe)
- **Pas de conteneur PostgreSQL local**

## 🚀 Déploiement

### Mode développement
```bash
# Avec le script de déploiement
./deploy.sh dev

# Ou manuellement
docker-compose up -d
```

**Application accessible sur**: http://localhost:8090

### Mode production
```bash
# Avec le script de déploiement  
./deploy.sh prod

# Ou manuellement
docker-compose -f docker-compose.prod.yml up -d
```

**Application accessible sur**: http://localhost

## 📁 Structure des fichiers Docker

- `Dockerfile` : Image PHP 8.2 avec extensions PostgreSQL
- `docker-compose.yml` : Configuration développement (port 8080)
- `docker-compose.prod.yml` : Configuration production (port 80)
- `.dockerignore` : Fichiers exclus du build
- `deploy.sh` : Script de déploiement automatisé

## 🛠️ Commandes utiles

### Construction manuelle
```bash
docker build -t appdaf:latest .
```

### Gestion des conteneurs
```bash
# Arrêter (développement)
docker-compose down

# Arrêter (production)
docker-compose -f docker-compose.prod.yml down

# Voir les logs
docker-compose logs -f
```

### Accès au conteneur
```bash
# Développement
docker exec -it appdaf-app bash

# Production
docker exec -it appdaf-app-prod bash
```

## 🔒 Sécurité

- ✅ Base de données externe sécurisée (Railway)
- ✅ Pas d'exposition de PostgreSQL en local
- ✅ Variables d'environnement configurées
- ⚠️ En production, considérez l'utilisation de Docker secrets

## 🌐 Test de l'API

Une fois déployé, testez l'API :

```bash
# Développement
curl http://localhost:8090/api/cni?nci=1895200000231

# Production  
curl http://localhost/api/cni?nci=1895200000231
```

## 🐛 Troubleshooting

### Problème de connexion à Railway
1. Vérifiez votre connexion internet
2. Vérifiez que les credentials Railway sont corrects
3. Testez la connexion depuis votre machine locale

### Problème avec le conteneur
```bash
# Voir les logs détaillés
docker logs appdaf-app

# Reconstruire l'image
docker-compose build --no-cache
```
