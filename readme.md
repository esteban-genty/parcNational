# �️ Parc National des Calanques – Application Web

> **Gestion interactive du Parc National avec cartes Leaflet, authentification JWT et dashboard admin/visiteur**

[![React](https://img.shields.io/badge/React-18.3-blue)](https://reactjs.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4-purple)](https://www.php.net/)
[![Leaflet](https://img.shields.io/badge/Leaflet-1.9-green)](https://leafletjs.com/)
[![Tests](https://img.shields.io/badge/Tests-100%25-brightgreen)](backend/tests/)

---

## 📚 DOCUMENTATION PROJET

| Document | Description | Pour qui ? |
|----------|-------------|------------|
| **README.md** *(ce fichier)* | Installation + Présentation générale | Tout le monde |
| **[AUDIT-PROJET.md](AUDIT-PROJET.md)** | État actuel détaillé + Ce qui manque | Développeurs + PM |
| **[TODO-PROJET.md](TODO-PROJET.md)** | Tâches prioritaires (demain 8 Oct) | Développeurs |
| **[PRESENTATION-DEMAIN.md](PRESENTATION-DEMAIN.md)** | Script présentation 8 Oct | Présentateur |
| **[DOCUMENTATION.md](DOCUMENTATION.md)** | Guide collaborateurs + Dépannage | Équipe dev |

---# 🌿 Parc National des Calanques – Marseille



[![Tests](https://img.shields.io/badge/Tests-33%2F33%20passed-success)](backend/tests)## 📌 Présentation

[![PHP](https://img.shields.io/badge/PHP-7.4-blue)](https://www.php.net/)Ce projet vise à gérer et valoriser le **Parc National des Calanques de Marseille** :  

[![React](https://img.shields.io/badge/React-18.3-61dafb)](https://react.dev/)- Gestion des visiteurs  

[![Leaflet](https://img.shields.io/badge/Leaflet-1.9-green)](https://leafletjs.com/)- Suivi des sentiers et zones protégées  

- Organisation des campings et hébergements  

## 📌 Présentation- Préservation des ressources naturelles  



Application web de gestion du **Parc National des Calanques de Marseille** avec :  ## 🗺️ Diagramme Mermaid

- 🔐 **Authentification JWT** (admin/visiteur)```mermaid

- 🗺️ **Cartes interactives Leaflet** (campings, sentiers, ressources naturelles)%% Exemple – ton fichier Mermaid intégré

- 🏕️ **Gestion des campings** avec réservationsgraph TD

- 🥾 **Suivi des sentiers** avec niveaux de difficulté    A[Visiteur] --> B[Réservation]

- 🌿 **Préservation des ressources naturelles** (Flore, Faune, Marine, Géologie)    B --> C[Sentiers]

- 📊 **Dashboard role-based** (admin/visiteur)    C --> D[Camping]

- 🌤️ **Widget météo** temps réel (API Open-Meteo)    D --> E[Ressources]

- 🔔 **Système de notifications** automatiques```



## 🚀 Démarrage Rapide---



### Prérequis## État du projet (30/09/2025)

- PHP 7.4+

- Node.js 18+### Backend

- MySQL 5.7+- Modèles CRUD réalisés pour :

- Composer  - User, Visiteur, Camping, Notification, RessourceNaturelle, CarteMembre, Sentier, Reservation

- npm ou yarn- Harmonisation et correction des endpoints (public/admin/visiteur/auth)

- Correction des inclusions et conflits de classe Database

### Installation- Sécurisation des endpoints admin par token JWT

- Création et correction des scripts de test d’intégration (REST Client)

```bash- Tests unitaires complets et **tous passés** (PHPUnit)

# 1. Cloner le dépôt- Authentification et flux utilisateur testés

git clone <votre-repo>- Scripts SQL corrigés et importés

cd parcNational- APIs/contrôleurs présents pour :

  - camping.php

# 2. Backend - Installer dépendances  - sentier.php

cd backend  - reservation.php

composer install  - notification.php

  - ressource_naturelle.php

# 3. Frontend - Installer dépendances  - carte_membre.php

cd ../frontend  - visiteur.php

npm install  - utilisateur.php



# 4. Base de données - Importer le schéma### Frontend

mysql -u root -p < backend/database/parc_national.sql- Projet React/Vite (dossier `frontend`)

- Build réussi (`npm run build`)

# 5. Peupler la base avec données réelles- À vérifier : intégration complète avec les APIs backend

php backend/scripts/populate-database.php

```### Tests

- Tous les tests backend passent (42 tests, 118 assertions)

### Lancer l'application- Création et correction des tests d’intégration pour les endpoints principaux

- À compléter : tests d’intégration supplémentaires, tests frontend

```bash

# Terminal 1 : Backend PHP (port 8080)---

cd backend

php -S localhost:8080## TODO LISTE



# Terminal 2 : Frontend React (port 5173)### Backend

cd frontend- [ ] Compléter les API/contrôleurs pour toutes les entités

npm run dev- [ ] Ajouter des tests d’intégration pour les flux complexes

```- [ ] Vérifier la cohérence des routes et la sécurité



🌐 **Accès :**### Frontend

- Frontend : http://localhost:5173- [ ] Vérifier l’intégration avec le backend (appels API, affichage)

- Backend API : http://localhost:8080- [ ] Ajouter des tests frontend si besoin



## 📂 Structure du Projet### Documentation

- [ ] Documenter chaque endpoint/API

```- [ ] Ajouter des exemples d’utilisation (requêtes, réponses)

parcNational/- [ ] Mettre à jour cette liste au fil de l’avancement

├── backend/

│   ├── api/---

│   │   ├── auth/           # Login, register, profile

│   │   ├── public/         # Endpoints publics (sentiers, ressources)## Commandes utiles

│   │   ├── admin/          # Endpoints admin (users)

│   │   └── camping.php     # API CRUD campings### Lancer les tests backend

│   ├── models/             # User, Camping, Sentier, Notification, etc.```sh

│   ├── controllers/        # AuthControllercd backend

│   ├── utils/              # JWTHandler, Validator, AuthMiddlewarephp vendor\bin\phpunit --testdox

│   ├── scripts/            # populate-database.php, create-admin.php```

│   └── tests/              # Tests PHPUnit (33 tests)

│### Builder le frontend

├── frontend/```sh

│   ├── src/cd frontend

│   │   ├── components/npm run build

│   │   │   ├── Maps/       # CampingsMap, SentiersMap, RessourcesMap```

│   │   │   ├── Dashboard/  # AdminDashboard, VisiteurDashboard

│   │   │   └── ...### Lancer le frontend en mode dev

│   │   ├── pages/          # HomePage, LoginPage, Dashboard, etc.```sh

│   │   ├── utils/          # icons.js (FontAwesome)cd frontend

│   │   ├── hooks/          # useAPI.jsnpm run dev

│   │   └── css/            # Styles globaux```

│   └── public/             # Assets statiques

│---

├── .gitignore              # Fichiers à ignorer

├── README.md               # Ce fichier## Points de vigilance

├── TODO-LIST-COMPLETE.md   # Liste des tâches- Bien vérifier la structure SQL avant de lancer les tests

└── COLLABORATION.md        # Guide pour collaborateurs- Toujours relancer les tests après une fusion de branche

```- Documenter chaque nouvelle fonctionnalité ou correction



## 🗺️ Architecture Technique---



### Backend (PHP 7.4)## À faire prochainement

- **Framework** : Vanilla PHP avec architecture MVC- Finaliser toutes les APIs

- **Base de données** : MySQL avec PDO- Ajouter des tests d’intégration et frontend

- **Authentification** : JWT (JSON Web Tokens)- Vérifier la cohérence globale (back + front)

- **Tests** : PHPUnit (33 tests, 104 assertions)- Mettre à jour la documentation technique

- **APIs** : RESTful avec CORS

### Frontend (React 18)
- **Build tool** : Vite
- **Router** : React Router v6
- **Cartes** : Leaflet + react-leaflet
- **Icons** : FontAwesome
- **Styles** : CSS modules + animations custom

## 📊 Base de Données

### Tables Principales
- `utilisateur` : Comptes admin/visiteur
- `camping` : 7 campings des Calanques
- `sentier` : 10 sentiers avec difficultés
- `ressource_naturelle` : 24 ressources (Flore, Faune, Marine, Géologie)
- `reservation` : Réservations campings
- `notification` : Alertes automatiques
- `carte_membre` : Cartes de membre visiteurs

### Données Réelles
✅ **7 campings** : Calanques, Sormiou, Morgiou, En Vau, Port-Miou, Sugiton, Les Goudes  
✅ **10 sentiers** : GR51, Sormiou, En Vau, Sugiton, Belvédère, etc.  
✅ **24 ressources** : Pin d'Alep, Faucon pèlerin, Posidonie, Calcaire urgonien, etc.

## 🔧 Commandes Utiles

### Backend
```bash
# Tests unitaires
cd backend
php vendor/bin/phpunit --testdox

# Créer un admin
php scripts/create-admin.php

# Peupler la base
php scripts/populate-database.php
```

### Frontend
```bash
# Mode développement
npm run dev

# Build production
npm run build

# Preview build
npm run preview

# Linter
npm run lint
```

## 🧪 Tests

### Backend (PHPUnit)
```bash
cd backend
php vendor/bin/phpunit --testdox
```

**Résultats :** ✅ 33/33 tests passés (104 assertions)

**Couverture :**
- ✅ Authentification (login/register)
- ✅ JWT (génération/validation/refresh)
- ✅ CRUD (User, Camping, Sentier, Notification, Ressource, Reservation, CarteMembre)
- ✅ Validation (email, password, rôles)

## 📖 Documentation

- **[TODO-LIST-COMPLETE.md](./TODO-LIST-COMPLETE.md)** : État d'avancement détaillé
- **[COLLABORATION.md](./COLLABORATION.md)** : Guide pour synchroniser avec la branche `test`
- **[backend/cahier-tests-unitaires.md](./backend/cahier-tests-unitaires.md)** : Planning et résultats tests

## 🤝 Collaboration

### Branches
- `test` : Branche principale de développement (35 commits en avance)
- `auth` : Authentification
- `homepage` : Page d'accueil
- `dashboard` : Dashboard
- `notification` : Notifications

### Workflow Git
Voir [COLLABORATION.md](./COLLABORATION.md) pour :
- Récupérer les nouveautés de `test`
- Synchroniser votre branche
- Résoudre les conflits
- Créer une Pull Request

## 🎨 Fonctionnalités Principales

### Pour les Visiteurs
- 📍 Consulter les **sentiers** avec carte interactive et difficultés
- 🏕️ Réserver un **camping** avec disponibilités en temps réel
- 🌿 Découvrir les **ressources naturelles** du parc
- 🌤️ Consulter la **météo** des Calanques (3 jours)
- 🔔 Recevoir des **notifications** (sentiers fermés, alertes)

### Pour les Admins
- 📊 **Dashboard** avec statistiques
- ✏️ **CRUD complet** : campings, sentiers, ressources
- 👥 **Gestion des utilisateurs**
- 🔔 **Génération de notifications** système
- 📈 **Graphiques** et métriques

## 🚨 Points Importants

### Sécurité
✅ Tokens JWT avec expiration  
✅ Validation des entrées (Validator.php)  
✅ Sanitization XSS  
✅ Middleware d'authentification  
✅ Role-based access control  

### Performance
✅ Build optimisé Vite  
✅ Lazy loading des composants  
✅ Cache API  
✅ Marqueurs Leaflet optimisés  

### Maintenance
✅ Code commenté  
✅ Tests unitaires complets  
✅ Documentation à jour  
✅ Structure modulaire  

## 📞 Support

**Questions techniques :** Ouvrir une issue sur GitHub  
**Bugs :** Créer un rapport détaillé avec logs  
**Features :** Proposer dans les discussions

## 📜 Licence

Projet éducatif - Parc National des Calanques

---

**Dernière mise à jour :** 7 octobre 2025  
**Version :** 2.0.0  
**Statut :** 🟢 Production Ready
