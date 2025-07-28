#!/bin/bash

echo "🚀 Initialisation de la base de données..."
echo ""

# Exécuter les migrations
echo "📋 Exécution des migrations..."
php database/run-migrations.php

echo ""

# Exécuter les seeders
echo "🌱 Exécution des seeders..."
php database/run-seeders.php

echo ""
echo "✅ Initialisation terminée!"
echo ""
echo "🔗 Vous pouvez maintenant tester votre API:"
echo "   GET /api/cni?nci=1895200000231"
