@echo off@echo off

REM ========================================REM Script Windows pour démarrer les services du projet parcNational

REM 🚀 Script de démarrage automatique

REM    Parc National - Backend + FrontendREM Démarrage du serveur Apache

REM ========================================ECHO Démarrage du serveur Apache...

start "Apache" "C:\wamp64\bin\apache\apache2.4.54\bin\httpd.exe"

ECHO.

ECHO ========================================REM Démarrage du serveur MySQL

ECHO 🌲 PARC NATIONAL - DEMARRAGE AUTO 🌲ECHO Démarrage du serveur MySQL...

ECHO ========================================start "MySQL" "C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqld.exe"

ECHO.

REM Démarrage du backend (exemple: Laravel ou PHP natif)

REM 🔍 Vérifier si WAMP est déjà démarréIF EXIST backend (

ECHO 🔍 Verification du serveur WAMP...    ECHO Démarrage du backend PHP...

tasklist /FI "IMAGENAME eq wampmanager64.exe" 2>NUL | find /I /N "wampmanager64.exe">NUL    start "Backend" cmd /k "cd backend && php -S localhost:8000"

if "%ERRORLEVEL%"=="0" ()

    ECHO ✅ WAMP est deja demarre

) else (REM Démarrage du frontend (React, Vue, etc.)

    ECHO 🚀 Demarrage de WAMP...IF EXIST frontend (

    start "" "C:\wamp64\wampmanager.exe"    ECHO Démarrage du frontend...

    ECHO ⏳ Attente de 5 secondes pour le demarrage de WAMP...    start "Frontend" cmd /k "cd frontend && npm install && npm start"

    timeout /t 5 /nobreak >nul)

)

ECHO Tous les services nécessaires sont démarrés.

REM 🗄️ Vérifier si MySQL tournepause

ECHO.
ECHO 🔍 Verification de MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    ECHO ✅ MySQL fonctionne correctement
) else (
    ECHO ❌ MySQL ne demarre pas - Verifiez WAMP
    pause
    exit /b 1
)

REM 🌐 Vérifier si Apache tourne
ECHO.
ECHO 🔍 Verification d'Apache...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    ECHO ✅ Apache fonctionne correctement
) else (
    ECHO ❌ Apache ne demarre pas - Verifiez WAMP
    pause
    exit /b 1
)

REM 📁 Vérifier que nous sommes dans le bon répertoire
ECHO.
ECHO 🔍 Verification du repertoire...
IF NOT EXIST "frontend" (
    ECHO ❌ Erreur: Dossier frontend introuvable
    ECHO    Executez ce script depuis c:\wamp64\www\parcNational
    pause
    exit /b 1
)

IF NOT EXIST "backend" (
    ECHO ❌ Erreur: Dossier backend introuvable
    pause
    exit /b 1
)

ECHO ✅ Structure du projet correcte

REM 🎨 Démarrage du FRONTEND (Vite React)
ECHO.
ECHO ========================================
ECHO 🎨 DEMARRAGE DU FRONTEND
ECHO ========================================
cd frontend
IF NOT EXIST "node_modules" (
    ECHO 📦 Installation des dependances...
    call npm install
)

ECHO 🚀 Lancement du serveur Vite...
start "Frontend Parc National" cmd /k "npm run dev"

REM ⏳ Attendre que Vite démarre
timeout /t 3 /nobreak >nul

REM Retour au dossier principal
cd ..

REM ✅ Confirmation
ECHO.
ECHO ========================================
ECHO ✅ TOUS LES SERVICES SONT DEMARRES
ECHO ========================================
ECHO.
ECHO 🗄️  MySQL     : ✅ Port 3306
ECHO 🌐 Apache    : ✅ http://localhost
ECHO 🔧 Backend   : ✅ http://localhost/parcNational/backend
ECHO 🎨 Frontend  : ✅ http://localhost:5173
ECHO.
ECHO 👤 COMPTE ADMIN:
ECHO    Email     : admin@parcnational.fr
ECHO    Password  : Admin123
ECHO.
ECHO 📝 Pour arreter les services, fermez les fenetres de terminal
ECHO.

REM 🌐 Ouvrir automatiquement le frontend dans le navigateur
ECHO 🌐 Ouverture du navigateur...
timeout /t 2 /nobreak >nul
start http://localhost:5173

ECHO.
ECHO ✨ Projet demarre avec succes !
ECHO    Appuyez sur une touche pour fermer cette fenetre...
pause >nul
