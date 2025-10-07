# 🚀 DÉMARRAGE RAPIDE - Parc National

## ⚡ LANCEMENT AUTOMATIQUE

### Option 1: Script Windows (Recommandé)
```cmd
# Double-cliquer sur le fichier:
start.bat

# OU depuis PowerShell/CMD:
cd c:\wamp64\www\parcNational
.\start.bat
```

**Ce script fait TOUT automatiquement:**
- ✅ Démarre WAMP (MySQL + Apache)
- ✅ Vérifie que les services tournent
- ✅ Lance le frontend React (Vite)
- ✅ Ouvre le navigateur sur http://localhost:5173

### Option 2: Manuel

#### 1. Démarrer WAMP
- Double-cliquer sur l'icône WAMP
- Attendre que l'icône devienne verte

#### 2. Démarrer le Frontend
```cmd
cd c:\wamp64\www\parcNational\frontend
npm run dev
```

---

## 👤 CONNEXION

### Compte Administrateur
```
URL      : http://localhost:5173
Email    : admin@parcnational.fr
Password : Admin123
```

### Compte Visiteur (à créer)
Cliquer sur "S'inscrire" depuis la page de connexion

---

## 📍 URLs IMPORTANTES

| Service | URL |
|---------|-----|
| 🎨 Frontend | http://localhost:5173 |
| 🔧 Backend API | http://localhost/parcNational/backend/api |
| 🗄️ PhpMyAdmin | http://localhost/phpmyadmin |
| 🌐 WAMP | http://localhost |

---

## 🔔 NOTIFICATIONS

### Voir les Notifications
Les notifications s'affichent automatiquement dans le dashboard après connexion.

### Créer des Notifications de Test
```cmd
cd c:\wamp64\www\parcNational\backend
php scripts\create-notifications.php
```

Cela créera 5 notifications de test dans la base de données.

---

## 🛠️ SCRIPTS UTILES

### Créer un Compte Admin
```cmd
cd c:\wamp64\www\parcNational\backend
php scripts\create-admin.php
```

### Créer des Notifications
```cmd
cd c:\wamp64\www\parcNational\backend
php scripts\create-notifications.php
```

### Lancer les Tests
```cmd
cd c:\wamp64\www\parcNational\backend
.\vendor\bin\phpunit.bat --testdox
```

---

## 🐛 RÉSOLUTION DE PROBLÈMES

### Le frontend ne démarre pas
```cmd
cd frontend
npm install
npm run dev
```

### WAMP ne démarre pas
1. Vérifier qu'aucun autre serveur n'utilise le port 80 (IIS, Skype, etc.)
2. Fermer WAMP complètement
3. Relancer WAMP en tant qu'administrateur

### Erreur "Cannot connect to database"
1. Vérifier que WAMP est démarré (icône verte)
2. Vérifier les identifiants dans `backend/models/Database.php`:
   - Host: `localhost`
   - User: `root`
   - Password: `` (vide)
   - Database: `parc_national`

### Erreur CORS
1. Vérifier que le frontend tourne sur `http://localhost:5173`
2. Vérifier les en-têtes CORS dans les fichiers API
3. Redémarrer WAMP

### Notifications vides
```cmd
# Exécuter le script de création:
cd backend
php scripts\create-notifications.php

# Vérifier dans PhpMyAdmin:
# Table: notification
# Devrait contenir 5 entrées
```

---

## 📊 STRUCTURE DU PROJET

```
parcNational/
├── backend/              # API PHP + MySQL
│   ├── api/             # Points d'entrée API
│   │   ├── admin/       # API admin (camping, users)
│   │   └── public/      # API publique (notifications)
│   ├── models/          # Modèles de données
│   ├── controllers/     # Contrôleurs (Auth)
│   ├── utils/           # Middleware, JWT, Validators
│   ├── scripts/         # Scripts utilitaires
│   └── tests/           # Tests unitaires PHPUnit
│
├── frontend/            # Application React + Vite
│   ├── src/
│   │   ├── components/  # Composants React
│   │   ├── pages/       # Pages de l'app
│   │   ├── css/         # Styles CSS
│   │   └── assets/      # Images et ressources
│   └── package.json
│
├── start.bat            # 🚀 Script de démarrage auto
├── GUIDE-CORRECTIONS.md # Documentation complète
└── README.md            # Ce fichier
```

---

## 🧪 TESTS

### Tous les Tests
```cmd
cd backend
.\vendor\bin\phpunit.bat --testdox
```

### Tests Unitaires Seulement
```cmd
.\vendor\bin\phpunit.bat --testdox tests/Unit/
```

### Tests d'Intégration
```cmd
.\vendor\bin\phpunit.bat --testdox tests/Integration/
```

**Résultat attendu:** ✅ 32 tests, 95 assertions (100% de réussite)

---

## 📝 TODO / AMÉLIORATIONS FUTURES

### Base de Données
- [ ] Ajouter colonne `utilisateur_id` à la table `notification`
- [ ] Ajouter colonne `lu` (boolean) à la table `notification`
- [ ] Créer table de liaison `notification_utilisateur`

### Fonctionnalités
- [ ] Marquer les notifications comme lues
- [ ] Système de notifications en temps réel (WebSocket)
- [ ] Pagination des notifications
- [ ] Filtres par date/type
- [ ] Notifications push (navigateur)

### Sécurité
- [ ] Rate limiting sur les API
- [ ] Validation plus stricte des entrées
- [ ] Logs d'audit admin
- [ ] 2FA pour les admins

---

## 📞 SUPPORT

Pour toute question ou problème:
1. Consulter `GUIDE-CORRECTIONS.md` pour les détails techniques
2. Vérifier les commentaires dans le code (avec emojis 🔥)
3. Examiner les logs dans la console du navigateur (F12)

---

**Dernière mise à jour:** 2 octobre 2025  
**Version:** 1.0.0  
**Auteur:** Équipe Parc National
