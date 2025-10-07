# 📚 DOCUMENTATION - Parc National des Calanques

**Projet** : Application Web Gestion Parc National  
**Date** : 7 Octobre 2025  
**Branche** : test

---

## 📖 DOCUMENTS IMPORTANTS

### 🎯 Documents Actifs (À lire en priorité)

| Document | Description | Pour qui ? |
|----------|-------------|------------|
| **README.md** | Guide installation et présentation générale | Tout le monde |
| **AUDIT-PROJET.md** | État actuel du projet + ce qui manque | Développeurs + Chef de projet |
| **TODO-PROJET.md** | Liste des tâches à faire (priorités) | Développeurs |
| **PRESENTATION-DEMAIN.md** | Script pour présentation 8 octobre | Présentateur |

---

## 🚀 DÉMARRAGE RAPIDE

### Installation
```bash
# 1. Cloner le projet
git clone <repo>
cd parcNational

# 2. Installer dépendances backend
cd backend
composer install

# 3. Installer dépendances frontend
cd ../frontend
npm install

# 4. Peupler la base de données
php backend/scripts/populate-database.php

# 5. Démarrer les serveurs
# Terminal 1 (Backend)
cd backend
php -S localhost:8080

# Terminal 2 (Frontend)
cd frontend
npm run dev
```

### Accès
- **Frontend** : http://localhost:5173
- **Backend API** : http://localhost:8080
- **Admin** : admin@example.com / adminpassword
- **Visiteur** : test@example.com / testpassword

---

## 📂 STRUCTURE DU PROJET

```
parcNational/
├── frontend/               React + Vite
│   ├── src/
│   │   ├── components/
│   │   │   ├── Maps/      ✅ Cartes Leaflet (3 fichiers)
│   │   │   └── Dashboard/ ⚠️ Dashboard (admin incomplet)
│   │   ├── pages/         ✅ Pages complètes
│   │   ├── hooks/         ✅ useAPI.js
│   │   └── utils/         ✅ icons.js (FontAwesome)
│   └── package.json
│
├── backend/                PHP + MySQL
│   ├── api/
│   │   ├── camping.php    ✅ CRUD complet
│   │   ├── admin/         ⚠️ Incomplet (users.php seulement)
│   │   └── public/        ✅ APIs publiques
│   ├── models/            ✅ Modèles CRUD
│   ├── utils/             ✅ JWT + Middleware
│   └── tests/             ✅ PHPUnit (8 tests)
│
├── AUDIT-PROJET.md        📋 État actuel détaillé
├── TODO-PROJET.md         📝 Liste tâches prioritaires
├── PRESENTATION-DEMAIN.md 🎤 Script présentation
└── README.md              📖 Documentation générale
```

---

## 🔑 POINTS CLÉS DU PROJET

### ✅ Ce qui fonctionne
- Authentification JWT complète
- 3 cartes Leaflet interactives
- API CRUD backend (campings, sentiers, ressources)
- Dashboard visiteur fonctionnel
- Base de données réelle (41 entrées Calanques)
- Tests unitaires (100% pass)

### ⚠️ Ce qui manque
- Interface admin CRUD (tableaux + formulaires)
- Gestion des réservations admin
- Coordonnées GPS réelles en BDD

---

## 👥 POUR LES COLLABORATEURS

### Comment récupérer le travail sur la branche `test` ?

```bash
# 1. Fetch toutes les branches
git fetch origin

# 2. Voir toutes les branches
git branch -a

# 3. Se positionner sur test
git checkout test
git pull origin test

# 4. Créer votre branche perso depuis test
git checkout -b dev-votre-nom

# 5. Travailler sur votre branche
# ... vos modifications ...

# 6. Commiter vos changements
git add .
git commit -m "feat: description"

# 7. Push votre branche
git push origin dev-votre-nom

# 8. Créer une Pull Request vers test
```

### Synchronisation avec test

```bash
# Mettre à jour votre branche avec les derniers changements de test
git checkout dev-votre-nom
git fetch origin
git merge origin/test

# Résoudre les conflits si besoin
# Puis push
git push origin dev-votre-nom
```

---

## 🧪 TESTS

### Tests Backend (PHPUnit)
```bash
cd backend
./vendor/bin/phpunit tests/
```

### Tests API (HTTP Files)
Ouvrir dans VSCode avec extension "REST Client" :
- `backend/tests/ApiCampingTest.http`
- `backend/tests/ApiCrudTest.http`
- `backend/tests/ApiIntegrationTest.http`

---

## 🐛 DÉPANNAGE

### Problème : "Access-Control-Allow-Origin"
**Solution** : Vérifier CORS dans `backend/utils/ResponseHelper.php`

### Problème : Cartes ne s'affichent pas
**Solution** : 
```bash
cd frontend
npm install leaflet react-leaflet
```

### Problème : Marqueurs Leaflet sans icône
**Solution** : Vérifier CDN dans `Maps/*.jsx` :
```javascript
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/...',
  iconUrl: 'https://cdnjs.cloudflare.com/...',
  shadowUrl: 'https://cdnjs.cloudflare.com/...'
});
```

### Problème : API retourne 401 Unauthorized
**Solution** : 
1. Vérifier token dans localStorage
2. Vérifier `Authorization: Bearer <token>` dans headers
3. Token expiré ? → Refresh avec `/api/auth/refresh.php`

---

## 📞 CONTACT

**Chef de projet** : [Votre nom]  
**Repo GitHub** : esteban-genty/parcNational  
**Branche active** : test

---

## 📜 HISTORIQUE

| Date | Version | Changements |
|------|---------|-------------|
| 7 Oct 2025 | v0.9 | Cartes Leaflet + Refactor + FontAwesome |
| 6 Oct 2025 | v0.8 | Dashboard visiteur complet |
| 5 Oct 2025 | v0.7 | Authentification JWT |
| 4 Oct 2025 | v0.6 | API CRUD backend |

**Prochaine version** : v1.0 (Admin CRUD complet)
