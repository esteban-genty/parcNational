# 📋 TODO LIST - Parc National des Calanques

**Dernière mise à jour** : 7 Octobre 2025  
**Branche active** : test

---

## 🚨 URGENT - À FAIRE DEMAIN (8 Octobre)

### 1. Interface Admin CRUD Campings (3h)
**Priorité** : 🔴 CRITIQUE

**Tâches** :
- [ ] Créer `frontend/src/pages/admin/CampingsAdminPage.jsx`
  - Tableau des campings avec actions (Modifier, Supprimer)
  - Bouton "Ajouter un camping"
  - Formulaire modal (Création/Modification)
- [ ] Créer `frontend/src/components/Admin/CampingForm.jsx`
  - Champs : nom, localisation, capacité
  - Validation formulaire
  - Soumission POST/PUT vers API
- [ ] Intégrer dans le routing Dashboard admin
- [ ] Tester CRUD complet (Create, Read, Update, Delete)

**Backend déjà prêt** : ✅ `api/camping.php` (POST/PUT/DELETE avec AuthMiddleware)

---

### 2. Interface Admin CRUD Sentiers (3h)
**Priorité** : 🔴 CRITIQUE

**Tâches** :
- [ ] Créer `frontend/src/pages/admin/SentiersAdminPage.jsx`
  - Tableau des sentiers avec difficulté colorée
  - Actions : Modifier, Supprimer
  - Bouton "Ajouter un sentier"
- [ ] Créer `frontend/src/components/Admin/SentierForm.jsx`
  - Champs : nom, difficulté (select facile/moyen/difficile), description
  - Validation
  - Soumission API
- [ ] Créer `backend/api/admin/sentiers.php`
  - POST : Créer sentier (requireAdmin)
  - PUT : Modifier sentier (requireAdmin)
  - DELETE : Supprimer sentier (requireAdmin)
- [ ] Mettre à jour `useAPI.js` : hooks CRUD sentiers admin

**Backend modèle prêt** : ✅ `models/Sentier.php` (create, update, delete)

---

### 3. Interface Admin CRUD Ressources Naturelles (3h)
**Priorité** : 🔴 CRITIQUE

**Tâches** :
- [ ] Créer `frontend/src/pages/admin/RessourcesAdminPage.jsx`
  - Tableau groupé par type (Flore, Faune, Marine, Géologie)
  - Actions : Modifier, Supprimer
  - Bouton "Ajouter une ressource"
- [ ] Créer `frontend/src/components/Admin/RessourceForm.jsx`
  - Champs : type (select), nom, état (select)
  - Validation
- [ ] Créer `backend/api/admin/ressources.php`
  - CRUD complet avec AuthMiddleware
- [ ] Compléter `models/RessourceNaturelle.php`
  - Ajouter méthodes update() et delete()

---

### 4. Gestion des Réservations Admin (2h)
**Priorité** : 🟠 HAUTE

**Tâches** :
- [ ] Créer `frontend/src/pages/admin/ReservationsAdminPage.jsx`
  - Liste toutes les réservations (tous visiteurs)
  - Colonnes : Visiteur, Camping, Dates, Statut
  - Actions : Voir détails, Valider, Refuser, Supprimer
- [ ] Mettre à jour `backend/api/visiteurs/reservations.php`
  - Ajouter endpoint GET pour admin (toutes réservations)
  - Ajouter statut réservation (en_attente, validee, refusee)

---

## 🟡 MOYEN TERME (Cette semaine)

### 5. Ajouter Coordonnées GPS Réelles (1h)
**Priorité** : 🟡 MOYENNE

**Tâches** :
- [ ] Ajouter colonnes `latitude` et `longitude` dans tables :
  - `camping` (7 campings)
  - `sentier` (10 sentiers points de départ)
  - `ressource_naturelle` (24 ressources)
- [ ] Script SQL pour UPDATE avec coordonnées Calanques réelles
- [ ] Modifier Maps pour utiliser BDD au lieu de `getPosition(index)`

---

### 6. Formulaire Réservation Visiteur (2h)
**Priorité** : 🟡 MOYENNE

**Tâches** :
- [ ] Créer `frontend/src/components/Reservation/ReservationForm.jsx`
  - Sélection camping
  - Calendrier dates (date_debut, date_fin)
  - Vérification disponibilité
- [ ] Intégrer dans `CampingsPage.jsx` (bouton "Réserver")
- [ ] Connecter à `api/visiteurs/reservations.php` (POST)

---

### 7. Dashboard Admin - Section Utilisateurs (2h)
**Priorité** : 🟡 MOYENNE

**Tâches** :
- [ ] Créer `frontend/src/pages/admin/UsersAdminPage.jsx`
  - Liste des utilisateurs (admin + visiteurs)
  - Actions : Voir profil, Bloquer/Débloquer, Supprimer
- [ ] Utiliser `backend/api/admin/users.php` (déjà existant)

---

### 8. Améliorer Dashboard Admin (1h)
**Priorité** : 🟡 MOYENNE

**Tâches** :
- [ ] Remplacer stats cards par boutons d'action
  - "Gérer les {stats.campings} campings" → lien vers CampingsAdminPage
  - "Gérer les {stats.sentiers} sentiers" → lien vers SentiersAdminPage
  - etc.
- [ ] Ajouter graphiques (Chart.js)
  - Réservations par mois
  - Sentiers les plus empruntés

---

## 🟢 AMÉLIORATIONS (Optionnel)

### 9. Export de Données (1h)
**Priorité** : 🟢 BASSE

**Tâches** :
- [ ] Bouton "Exporter PDF" dans pages admin
- [ ] Utiliser librairie `jsPDF` ou `react-pdf`
- [ ] Export : Liste campings, sentiers, réservations

---

### 10. Système de Recherche/Filtres (1h)
**Priorité** : 🟢 BASSE

**Tâches** :
- [ ] Barre de recherche dans pages admin
- [ ] Filtres : difficulté sentiers, type ressources, statut réservations
- [ ] Utiliser `Array.filter()` côté frontend

---

### 11. Tests E2E (2h)
**Priorité** : 🟢 BASSE

**Tâches** :
- [ ] Installer Cypress ou Playwright
- [ ] Tests admin : Login → Créer camping → Modifier → Supprimer
- [ ] Tests visiteur : Login → Voir carte → Faire réservation

---

### 12. Notifications en Temps Réel (3h)
**Priorité** : 🟢 BASSE

**Tâches** :
- [ ] WebSockets (Socket.io)
- [ ] Notification admin quand nouvelle réservation
- [ ] Badge rouge sur icône cloche

---

## 📝 RÉCAPITULATIF PLANNING

| Jour | Tâches | Durée | Priorité |
|------|--------|-------|----------|
| **8 Oct** | 1. CRUD Campings Admin | 3h | 🔴 CRITIQUE |
| **8 Oct** | 2. CRUD Sentiers Admin | 3h | 🔴 CRITIQUE |
| **9 Oct** | 3. CRUD Ressources Admin | 3h | 🔴 CRITIQUE |
| **9 Oct** | 4. Réservations Admin | 2h | 🟠 HAUTE |
| **10 Oct** | 5. Coordonnées GPS | 1h | 🟡 MOYENNE |
| **10 Oct** | 6. Formulaire Réservation | 2h | 🟡 MOYENNE |
| **11 Oct** | 7. Users Admin | 2h | 🟡 MOYENNE |
| **11 Oct** | 8. Dashboard Charts | 1h | 🟡 MOYENNE |

**Total heures critiques** : 11h  
**Total cette semaine** : 17h

---

## ✅ DÉJÀ TERMINÉ (Rappel)

### Frontend
- ✅ Pages visiteurs (Campings, Sentiers, Ressources)
- ✅ Cartes Leaflet interactives (3 maps)
- ✅ Authentification complète (Login, Register, Profile)
- ✅ Dashboard visiteur fonctionnel
- ✅ FontAwesome partout (25+ icons)
- ✅ Météo widget (API Open-Meteo)
- ✅ Animations CSS

### Backend
- ✅ API CRUD Camping (POST/PUT/DELETE admin)
- ✅ API CRUD Sentier (modèle prêt)
- ✅ API CRUD Réservation
- ✅ Authentification JWT + refresh tokens
- ✅ Middleware admin (AuthMiddleware)
- ✅ Génération notifications automatiques
- ✅ Tests unitaires PHPUnit (8 tests, 100% pass)
- ✅ Script populate-database.php (41 entrées réelles)

### Qualité
- ✅ Séparation responsabilités (Maps/)
- ✅ Code propre (refactor 600→104 lignes)
- ✅ Commits organisés (35 commits)
- ✅ Documentation (README, AUDIT, TODO)

---

## 🎯 OBJECTIF FIN DE SEMAINE

**Dashboard Admin 100% Fonctionnel** :
- [x] Voir statistiques
- [ ] Gérer campings (CRUD)
- [ ] Gérer sentiers (CRUD)
- [ ] Gérer ressources (CRUD)
- [ ] Voir/valider réservations
- [ ] Voir liste utilisateurs

**Date cible** : 11 Octobre 2025
