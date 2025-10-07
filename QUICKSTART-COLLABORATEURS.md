# 🎯 QUICK START - Collaborateurs

## 🚨 IMPORTANT : Synchronisation avec la branche `test`

La branche `test` a **40 commits en avance**. Vous devez synchroniser avant de continuer.

## 📋 Étapes Rapides

### 1️⃣ Récupérer les nouveautés de `test`

```bash
# Sauvegarder votre travail actuel
git add .
git commit -m "Sauvegarde avant sync avec test"

# Récupérer test
git fetch origin
git checkout test
git pull origin test

# Retourner sur votre branche
git checkout <votre-branche>  # auth, homepage, dashboard ou notification

# Fusionner test dans votre branche
git merge test
```

### 2️⃣ Résoudre les conflits (si nécessaire)

```bash
# Voir les fichiers en conflit
git status

# Ouvrir chaque fichier, résoudre manuellement
# Chercher: <<<<<<<, =======, >>>>>>>

# Marquer comme résolu
git add <fichier-résolu>

# Finaliser
git commit -m "Merge test into <votre-branche>"
```

### 3️⃣ Installer les nouvelles dépendances

```bash
# Backend (si nécessaire)
cd backend
composer install

# Frontend (nouvelles dépendances Leaflet)
cd frontend
npm install
```

### 4️⃣ Lancer l'application

```bash
# Terminal 1 : Backend
cd backend
php -S localhost:8080

# Terminal 2 : Frontend
cd frontend
npm run dev
```

## 🆕 Nouveautés sur `test` (Derniers 16 commits)

### 🗺️ Cartes Leaflet (3 composants)
- `CampingsMap.jsx` : Carte interactive 7 campings
- `SentiersMap.jsx` : Carte 10 sentiers avec difficultés
- `RessourcesMap.jsx` : Carte 24 ressources naturelles

### 📄 Pages Complètes (3 pages)
- `SentiersPage.jsx` : Carte + liste + légende
- `CampingsPage.jsx` : Carte + réservations
- `RessourcesNaturellesPage.jsx` : Carte + groupées par type

### 🎨 Design System
- `icons.js` : 25+ icônes FontAwesome
- Remplacement tous emojis ASCII
- Cohérence visuelle totale

### 🗄️ Base de Données
- `populate-database.php` : Script de peuplement
- 7 campings réels Calanques
- 10 sentiers réels
- 24 ressources naturelles réelles

### 📚 Documentation
- `README.md` : Doc complète professionnelle
- `COLLABORATION.md` : Guide sync branches (⭐ LISEZ-MOI)
- `TODO-LIST-COMPLETE.md` : État projet mis à jour
- `.gitignore` : Fichiers obsolètes archivés

## ⚠️ Fichiers Potentiellement en Conflit

Ces fichiers ont été modifiés, attendez-vous à des conflits :

1. **Frontend:**
   - `frontend/src/pages/DashboardPage.jsx` (600+ → 104 lignes)
   - `frontend/src/components/AnimatedHeader.jsx`
   - `frontend/src/components/Dashboard/AdminDashboard.jsx`
   - `frontend/src/components/Dashboard/VisiteurDashboard.jsx`
   - `frontend/src/components/Dashboard/DashboardNotificationsNew.jsx`
   - `frontend/src/components/Dashboard/MeteoWidget.jsx`
   - `frontend/src/pages/HomePageNew.jsx`
   - `frontend/src/utils/icons.js` (NOUVEAU)

2. **Backend:**
   - `backend/scripts/populate-database.php` (NOUVEAU)

3. **Documentation:**
   - `README.md` (réécrit complet)
   - `TODO-LIST-COMPLETE.md`
   - `.gitignore` (NOUVEAU)

## 🛠️ En Cas de Problème

### "Too many conflicts, I'm lost"
```bash
# Annuler le merge
git merge --abort

# Contacter Oussama pour aide
```

### "Je veux juste voir ce qui a changé"
```bash
git diff test..<votre-branche>
```

### "Je veux garder ma version"
```bash
# Pendant conflit, choisir votre version
git checkout --ours <fichier>
git add <fichier>
```

### "Je veux garder la version de test"
```bash
# Pendant conflit, choisir version test
git checkout --theirs <fichier>
git add <fichier>
```

## 📖 Documentation Complète

**Pour tout comprendre en détail :**

1. 📘 **[COLLABORATION.md](./COLLABORATION.md)** - Guide complet synchronisation
2. 📗 **[README.md](./README.md)** - Documentation technique complète
3. 📙 **[TODO-LIST-COMPLETE.md](./TODO-LIST-COMPLETE.md)** - État projet
4. 📕 **[DOCUMENTATION-INDEX.md](./DOCUMENTATION-INDEX.md)** - Index navigation

## 🎯 Checklist Post-Sync

Après avoir synchronisé avec `test`, vérifier :

- [ ] Pas d'erreurs compilation frontend (`npm run dev`)
- [ ] Backend démarre sans erreur (`php -S localhost:8080`)
- [ ] Tests passent (`php vendor/bin/phpunit`)
- [ ] Cartes Leaflet s'affichent correctement
- [ ] Icônes FontAwesome apparaissent (pas d'emojis ASCII)
- [ ] Pas de console errors dans le navigateur

## 💬 Communication

**Avant de merger :**
- ✅ Lire [COLLABORATION.md](./COLLABORATION.md)
- ✅ Tester localement
- ✅ Prévenir l'équipe

**En cas de blocage :**
- Contacter Oussama
- Créer une issue détaillée
- Ne jamais `git push --force` sur `test`

---

**Dernière mise à jour :** 7 octobre 2025  
**Commits en avance :** 40  
**Statut :** 🟢 Prêt pour sync

**Bonne synchronisation ! 🚀**
