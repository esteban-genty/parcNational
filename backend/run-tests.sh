#!/bin/bash

# Script pour exécuter les tests du Parc National des Calanques

echo "🧪 Exécution des tests unitaires et d'intégration..."

# Vérifier que PHPUnit est installé
if ! command -v ./vendor/bin/phpunit &> /dev/null; then
    echo "❌ PHPUnit n'est pas installé. Exécutez: composer install"
    exit 1
fi

# Créer la base de données de test si elle n'existe pas
echo "📊 Préparation de la base de données de test..."
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS parc_national_test;"
mysql -u root -p parc_national_test < scripts/database_setup.sql

# Exécuter les tests
echo "🚀 Lancement des tests..."
./vendor/bin/phpunit --testdox

# Générer le rapport de couverture (optionnel)
if [ "$1" = "--coverage" ]; then
    echo "📈 Génération du rapport de couverture..."
    ./vendor/bin/phpunit --coverage-html coverage/
    echo "📊 Rapport de couverture généré dans le dossier coverage/"
fi

echo "✅ Tests terminés!"
