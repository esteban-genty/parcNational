@echo off
REM Script Windows pour démarrer les services du projet parcNational

REM Démarrage du serveur Apache
ECHO Démarrage du serveur Apache...
start "Apache" "C:\wamp64\bin\apache\apache2.4.54\bin\httpd.exe"

REM Démarrage du serveur MySQL
ECHO Démarrage du serveur MySQL...
start "MySQL" "C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqld.exe"

REM Démarrage du backend (exemple: Laravel ou PHP natif)
IF EXIST backend (
    ECHO Démarrage du backend PHP...
    start "Backend" cmd /k "cd backend && php -S localhost:8000"
)

REM Démarrage du frontend (React, Vue, etc.)
IF EXIST frontend (
    ECHO Démarrage du frontend...
    start "Frontend" cmd /k "cd frontend && npm install && npm start"
)

ECHO Tous les services nécessaires sont démarrés.
pause
