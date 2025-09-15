echo "Ce script start.sh est prévu pour Linux/Mac. Pour Windows, utilise start.bat."
exit 0
#!/bin/bash

# Démarrage du serveur Apache (WAMP)
echo "Démarrage du serveur Apache..."
/c/wamp64/bin/apache/apache2.4.54/bin/httpd.exe

# Démarrage du serveur MySQL (WAMP)
echo "Démarrage du serveur MySQL..."
/c/wamp64/bin/mysql/mysql8.0.31/bin/mysqld.exe

# Démarrage du backend (exemple: Laravel)
if [ -d "backend" ]; then
    echo "Démarrage du backend Laravel..."
    cd backend
    php artisan serve &
    cd ..
fi

# Démarrage du frontend (exemple: React ou Vue)
if [ -d "frontend" ]; then
    echo "Démarrage du frontend..."
    cd frontend
    npm install
    npm start &
    cd ..
fi

echo "Tous les services nécessaires sont démarrés."