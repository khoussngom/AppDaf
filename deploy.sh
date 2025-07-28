#!/bin/bash

# Script de déploiement pour AppDaf

echo "🚀 Déploiement de AppDaf..."

# Vérifier que Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé"
    exit 1
fi

# Vérifier que Docker Compose est installé
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé"
    exit 1
fi

# Mode de déploiement (dev ou prod)
MODE=${1:-dev}

echo "📦 Construction de l'image Docker..."
docker build -t appdaf:latest .

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de la construction de l'image"
    exit 1
fi

if [ "$MODE" = "prod" ]; then
    echo "🏭 Déploiement en mode production..."
    docker-compose -f docker-compose.prod.yml down
    docker-compose -f docker-compose.prod.yml up -d
else
    echo "🔧 Déploiement en mode développement..."
    docker-compose down
    docker-compose up -d
fi

if [ $? -eq 0 ]; then
    echo "✅ Application déployée avec succès!"
    echo "🌐 Application accessible sur:"
    if [ "$MODE" = "prod" ]; then
        echo "   http://localhost"
    else
        echo "   http://localhost:8090"
    fi
    echo "📡 API endpoint: /api/cni?nci=1895200000231"
else
    echo "❌ Erreur lors du déploiement"
    exit 1
fi
