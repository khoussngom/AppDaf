#!/bin/bash

echo "=== Script de déploiement AppDaf ==="

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "composer.json" ]; then
    echo "Erreur: composer.json non trouvé. Veuillez exécuter ce script depuis la racine du projet."
    exit 1
fi

# Vérifier la présence du fichier .env
echo "Vérification du fichier .env..."
if [ ! -f ".env" ]; then
    echo "Fichier .env non trouvé. Copie depuis .env.example..."
    cp .env.example .env
    echo "⚠️  Veuillez configurer le fichier .env avec vos paramètres de base de données."
    exit 1
fi

echo "Contenu du fichier .env:"
cat .env
echo ""

# Tester la connexion à la base de données
echo "Test de la connexion à la base de données..."
php test-db.php

# Si le test réussit, démarrer le déploiement
if [ $? -eq 0 ]; then
    echo "✅ Test de connexion réussi. Démarrage du déploiement..."
    
    # Arrêter les conteneurs existants
    echo "Arrêt des conteneurs existants..."
    docker-compose down
    
    # Reconstruire et démarrer
    echo "Reconstruction et démarrage des conteneurs..."
    docker-compose up -d --build
    
    echo "✅ Déploiement terminé!"
    echo "🌐 Application disponible sur http://localhost:8090"
    
    # Afficher les logs en temps réel
    echo "📊 Logs de l'application (Ctrl+C pour arrêter):"
    docker-compose logs -f app
    
else
    echo "❌ Échec du test de connexion. Veuillez vérifier votre configuration."
    exit 1
fi
