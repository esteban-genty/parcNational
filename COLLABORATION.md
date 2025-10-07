# 🤝 Guide de Collaboration - Parc National des Calanques

## 📋 Table des Matières
1. [Récupérer le travail depuis la branche `test`](#récupérer-le-travail-depuis-test)
2. [Synchroniser vos branches avec `test`](#synchroniser-vos-branches)
3. [Workflow Git recommandé](#workflow-git-recommandé)
4. [Résolution de conflits](#résolution-de-conflits)
5. [Branches du projet](#branches-du-projet)

---

## 🔄 Récupérer le travail depuis la branche `test`

### Étape 1 : Mettre à jour votre dépôt local

```bash
# 1. Aller sur votre branche actuelle et sauvegarder votre travail
git status
git add .
git commit -m "Sauvegarde avant sync avec test"

# 2. Récupérer toutes les branches distantes
git fetch origin

# 3. Voir les derniers commits sur test
git log origin/test --oneline -20
```

### Étape 2 : Fusionner `test` dans votre branche

#### Si vous êtes sur `auth` :
```bash
git checkout auth
git merge origin/test
```

#### Si vous êtes sur `homepage` :
```bash
git checkout homepage
git merge origin/test
```

#### Si vous êtes sur `dashboard` :
```bash
git checkout dashboard
git merge origin/test
```

#### Si vous êtes sur `notification` :
```bash
git checkout notification
git merge origin/test
```

### Étape 3 : Résoudre les conflits (si nécessaire)

Si Git signale des conflits :

```bash
# Voir les fichiers en conflit
git status

# Ouvrir les fichiers et résoudre manuellement
# Cherchez les marqueurs : <<<<<<<, =======, >>>>>>>

# Une fois résolus, marquer comme résolus
git add <fichier-résolu>

# Finaliser la fusion
git commit -m "Merge origin/test into <votre-branche>"
```

---

## 🔀 Synchroniser vos branches avec `test`

### Méthode 1 : Merge (Recommandée pour travail collaboratif)

**Avantages** : Conserve l'historique complet, facile à comprendre

```bash
# Sur votre branche
git checkout <votre-branche>
git merge origin/test

# Si pas de conflits
git push origin <votre-branche>
```

### Méthode 2 : Rebase (Pour historique linéaire)

**Avantages** : Historique plus propre, mais peut être complexe

```bash
# Sur votre branche
git checkout <votre-branche>
git rebase origin/test

# Résoudre conflits un par un si nécessaire
git add <fichier-résolu>
git rebase --continue

# Force push (ATTENTION : communiquer avec l'équipe avant)
git push origin <votre-branche> --force-with-lease
```

⚠️ **ATTENTION** : N'utilisez `rebase` que si vous êtes seul sur votre branche !

---

## 🌳 Branches du Projet

| Branche | Responsable | Description | Statut |
|---------|-------------|-------------|--------|
| `test` | Oussama | Branche principale de développement | ✅ À jour (35 commits en avance) |
| `auth` | Collaborateur | Authentification JWT, login/register | 🔄 À synchroniser |
| `homepage` | Collaborateur | Page d'accueil, design | 🔄 À synchroniser |
| `dashboard` | Collaborateur | Dashboard admin/visiteur | 🔄 À synchroniser |
| `notification` | Collaborateur | Système de notifications | 🔄 À synchroniser |

---

## 📦 Nouveautés sur la branche `test` (Derniers 11 commits)

### 1. **FontAwesome Icons** (4e2d292)
- Fichier `frontend/src/utils/icons.js` créé
- 25+ icônes organisées par catégorie
- Remplacement de tous les emojis ASCII

### 2. **Composants Maps Leaflet** (33eec38)
- `CampingsMap.jsx` : Carte interactive des campings
- `SentiersMap.jsx` : Carte des sentiers avec difficulté
- `RessourcesMap.jsx` : Carte des ressources naturelles
- Marqueurs colorés par type/difficulté

### 3. **Pages complètes** (438bdaa)
- `SentiersPage.jsx` : Page sentiers avec carte + liste
- `CampingsPage.jsx` : Page campings avec réservations
- `RessourcesNaturellesPage.jsx` : Page ressources avec légende

### 4. **Refactorisation Dashboard** (33fb3b5)
- `DashboardPage.jsx` : Réduction de 600+ lignes à 104 lignes
- Séparation des responsabilités
- Import de pages séparées

### 5. **Remplacement des emojis** (7 commits)
- `AnimatedHeader.jsx` : Icons mountain, cog, user
- `AdminDashboard.jsx` : Icons camping, hiking, bell
- `VisiteurDashboard.jsx` : Icons map, camping, leaf
- `DashboardNotificationsNew.jsx` : Icons bell, plus, calendar
- `MeteoWidget.jsx` : Icon calendar
- `HomePageNew.jsx` : Icons mountain, leaf

### 6. **Script de population DB** (07be148)
- `backend/scripts/populate-database.php`
- 7 campings réels des Calanques
- 10 sentiers avec difficultés
- 24 ressources naturelles (Flore, Faune, Marine, Géologie)

---

## 🛠️ Workflow Git Recommandé

### Quotidien

```bash
# 1. Début de journée : récupérer les nouveautés
git fetch origin
git merge origin/test

# 2. Travailler sur votre feature
# ... faire vos modifications ...

# 3. Commit réguliers
git add .
git commit -m "feat: description claire"

# 4. Push de votre travail
git push origin <votre-branche>
```

### Avant de merger dans `test`

```bash
# 1. S'assurer que votre branche est à jour
git checkout <votre-branche>
git merge origin/test

# 2. Résoudre conflits si nécessaire
# 3. Tester localement
npm run dev  # Frontend
php -S localhost:8080  # Backend

# 4. Push
git push origin <votre-branche>

# 5. Créer une Pull Request sur GitHub/GitLab
```

---

## 🔧 Commandes Utiles

### Voir les différences avec test
```bash
git diff origin/test..<votre-branche>
```

### Annuler le dernier commit (garde les modifications)
```bash
git reset --soft HEAD~1
```

### Annuler les modifications non commitées
```bash
git restore <fichier>
# ou tout annuler :
git restore .
```

### Créer une nouvelle branche depuis test
```bash
git checkout test
git pull origin test
git checkout -b ma-nouvelle-feature
```

---

## 📞 Communication

### Avant de merger :
1. ✅ Vérifier que les tests passent
2. ✅ Tester localement les nouvelles fonctionnalités
3. ✅ Prévenir l'équipe sur Slack/Discord/Teams
4. ✅ Documenter les changements majeurs

### En cas de blocage :
- Créer une issue sur GitHub/GitLab
- Contacter Oussama pour les questions sur `test`
- Ne jamais forcer un push sur `test` sans accord

---

## 🚀 Démarrage Rapide Post-Sync

Après avoir synchronisé avec `test`, voici comment démarrer :

```bash
# 1. Installer les dépendances (si nouvelles)
cd frontend
npm install

cd ../backend
composer install

# 2. Lancer les serveurs
# Terminal 1 : Backend
cd backend
php -S localhost:8080

# Terminal 2 : Frontend
cd frontend
npm run dev

# 3. Accéder à l'application
# Frontend : http://localhost:5173
# Backend API : http://localhost:8080
```

---

## 📊 Structure Actuelle du Projet

```
parcNational/
├── backend/
│   ├── api/              # Endpoints API
│   ├── models/           # Modèles (User, Camping, Sentier, etc.)
│   ├── controllers/      # Contrôleurs
│   ├── utils/            # JWT, Validation, Auth
│   ├── scripts/          # Scripts utilitaires + populate-database.php
│   └── tests/            # Tests unitaires PHPUnit
│
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Maps/    # ✨ NOUVEAU : Composants Leaflet
│   │   │   └── Dashboard/
│   │   ├── pages/       # ✨ NOUVEAU : Pages séparées
│   │   ├── utils/       # ✨ NOUVEAU : icons.js
│   │   └── css/
│   └── public/
│
├── .gitignore           # ✨ NOUVEAU : Fichiers à ignorer
├── TODO-LIST.md         # ✨ MIS À JOUR : Liste des tâches
├── README.md            # ✨ MIS À JOUR : Documentation principale
└── COLLABORATION.md     # ✨ NOUVEAU : Ce fichier !
```

---

## ❓ FAQ

### Q: Je vois "Your branch is behind 'origin/test' by X commits"
**R:** Faites `git pull origin test` pour récupérer les nouveautés.

### Q: J'ai des conflits que je ne comprends pas
**R:** 
1. Faites `git merge --abort` pour annuler
2. Contactez Oussama
3. On résoudra ensemble

### Q: Je veux voir ce qui a changé sur test
**R:** `git log origin/test --oneline --graph -20`

### Q: Puis-je continuer sur ma branche sans merger test ?
**R:** Oui, mais **synchronisez régulièrement** (au moins 1x par semaine) pour éviter les gros conflits.

---

**Dernière mise à jour :** 7 octobre 2025  
**Auteur :** Oussama  
**Questions :** Contactez via Slack/Discord
