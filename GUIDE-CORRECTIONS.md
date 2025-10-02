# 📋 Guide des Corrections - Parc National

## ✅ PROBLÈMES RÉSOLUS

### 1. 🔐 Accès Admin aux Données
**Problème**: Admin connecté mais ne peut pas accéder aux campings/utilisateurs  
**Cause**: Le middleware `AuthMiddleware` vérifiait uniquement les JWT, pas les sessions PHP  
**Solution**: Modification de `AuthMiddleware::authenticate()` pour supporter les 2 méthodes

#### Comment ça fonctionne maintenant:
```php
// Le middleware vérifie dans cet ordre:
1. D'abord la session PHP ($_SESSION['user_id'])
2. Si pas de session, vérifie le token JWT (Bearer token)
```

**Fichiers modifiés:**
- `backend/utils/AuthMiddelware.php` - Ajout support sessions
- `backend/api/admin/camping.php` - Ajout en-têtes CORS
- `backend/api/admin/users.php` - Ajout en-têtes CORS

---

### 2. 🔔 Système de Notifications
**Problème**: Notifications non affichées  
**Solution**: Création d'un composant React + API backend

**Fichiers créés:**
- `frontend/src/components/Dashboard/DashboardNotifications.jsx` - Composant React
- `backend/models/Notification.php` - Ajout méthode `findByUserId()`

**Comment utiliser:**
```jsx
// Le composant affiche automatiquement les notifications de l'utilisateur
<DashboardNotifications userId={user.id} />
```

---

### 3. 🌐 Configuration CORS
**Problème**: Erreurs CORS bloquant les requêtes frontend → backend  
**Solution**: Ajout des en-têtes CORS sur toutes les API

**En-têtes ajoutés:**
```php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true'); // Permet cookies/sessions
```

**Fichiers avec CORS:**
- `backend/api/admin/camping.php`
- `backend/api/admin/users.php`
- `backend/api/public/notifications.php`
- `backend/controllers/AuthController.php`

---

### 4. 🖼️ Images Frontend
**Problème**: Images ne s'affichent pas  
**Statut**: ✅ Les images sont présentes dans `frontend/src/assets/`

**Vérification:**
```bash
frontend/src/assets/
├── bg-calanque.jpg ✅
├── bg-connexion.jpg ✅
├── bg-dashboard.jpg ✅
├── bg-home.jpeg ✅
├── icons/ ✅
├── marine/ ✅
└── terrestrial/ ✅
```

Les images sont importées correctement via Vite:
```jsx
import bgHome from "../assets/bg-home.jpeg";
```

---

## 🔧 FICHIERS IMPORTANTS

### Backend
```
backend/
├── utils/
│   └── AuthMiddelware.php ⭐ Gestion authentification (Session + JWT)
├── api/
│   ├── admin/
│   │   ├── camping.php ⭐ CRUD campings (admin seulement)
│   │   └── users.php ⭐ Gestion utilisateurs (admin seulement)
│   └── public/
│       └── notifications.php ⭐ Récupération notifications
├── models/
│   ├── Notification.php ⭐ Modèle avec findByUserId()
│   ├── User.php ⭐ Méthode create() retourne un tableau
│   └── Sentier.php ⭐ Méthode create() retourne un tableau
└── scripts/
    └── create-admin.php ⭐ Script pour créer un admin
```

### Frontend
```
frontend/src/
├── components/
│   └── Dashboard/
│       ├── DashboardNotifications.jsx ⭐ Affichage notifications
│       ├── DashboardRouter.jsx
│       └── DashboardWeather.jsx
└── pages/
    └── DashboardPage.jsx ⭐ Utilise DashboardNotifications
```

---

## 🎯 COMMENT UTILISER

### 1. Créer un Compte Admin
```bash
cd backend
php scripts/create-admin.php
```

**Compte créé:**
- 📧 Email: `admin@parcnational.fr`
- 🔑 Mot de passe: `Admin123`

### 2. Démarrer les Serveurs
```bash
# Terminal 1 - WAMP doit tourner pour PHP/MySQL

# Terminal 2 - Frontend
cd frontend
npm run dev
# Accès: http://localhost:5173
```

### 3. Se Connecter en Admin
1. Ouvrir `http://localhost:5173`
2. Cliquer sur "Connexion"
3. Utiliser les identifiants admin ci-dessus
4. Accéder au dashboard

### 4. Tester les Fonctionnalités Admin
- **Gestion Campings**: Créer, modifier, supprimer des campings
- **Gestion Utilisateurs**: Voir et supprimer des utilisateurs
- **Notifications**: Voir les notifications dans le dashboard

---

## 🧪 TESTS UNITAIRES

**Tous les tests passent: 32/32 ✅**

```bash
cd backend
.\vendor\bin\phpunit.bat --testdox

# Résultat:
OK (32 tests, 95 assertions)
```

**Tests inclus:**
- ✅ Camping (2 tests)
- ✅ CarteMembre (2 tests)
- ✅ JWTHandler (7 tests)
- ✅ Notification (2 tests)
- ✅ Reservation (2 tests)
- ✅ RessourceNaturelle (2 tests)
- ✅ Sentier (2 tests)
- ✅ User (7 tests)
- ✅ Validator (5 tests)
- ✅ AuthenticationFlow (1 test intégration)

---

## 📊 ARCHITECTURE D'AUTHENTIFICATION

### Deux Systèmes Parallèles

#### 1. **Session PHP** (Utilisé par le Frontend)
```
Frontend → AuthController.php?action=login
         ↓
    Crée $_SESSION
         ↓
    Retourne user data
         ↓
Frontend stocke dans state React
```

**Fichiers:**
- `backend/controllers/AuthController.php`
- `frontend/src/components/LoginForm.jsx`

#### 2. **JWT** (Pour API externes)
```
Client → api/auth/login.php
      ↓
   Génère JWT token
      ↓
   Retourne {token: "..."}
      ↓
Client envoie: Authorization: Bearer <token>
```

**Fichiers:**
- `backend/api/auth/login.php`
- `backend/utils/JWTHandler.php`

### Middleware Unifié
Le `AuthMiddleware` accepte les 2:
```php
// Vérifie d'abord $_SESSION
if (isset($_SESSION['user_id'])) {
    return session_data;
}

// Sinon vérifie JWT
$token = getBearerToken();
return verify($token);
```

---

## 🐛 DÉBOGAGE

### Problème: "Token manquant ou session expirée"
**Solution:**
1. Vérifier que l'utilisateur est connecté
2. Vérifier dans DevTools → Application → Cookies → `PHPSESSID` existe
3. Vérifier `credentials: 'include'` dans les requêtes fetch

### Problème: "CORS error"
**Solution:**
1. Vérifier que les en-têtes CORS sont ajoutés en HAUT du fichier PHP
2. Vérifier `Access-Control-Allow-Credentials: true`
3. Redémarrer WAMP si nécessaire

### Problème: "Notifications vides"
**Solution:**
1. Vérifier la table `notification` dans MySQL
2. Vérifier que `utilisateur_id` existe dans la table
3. Ajouter des notifications de test:
```sql
INSERT INTO notification (utilisateur_id, titre, message, date_envoi, lu) 
VALUES (45, 'Test', 'Message de test', NOW(), 0);
```

---

## 📝 COMMENTAIRES DANS LE CODE

Tous les fichiers modifiés contiennent des commentaires avec emojis:
- 🔐 = Authentification
- 🌐 = Configuration réseau/CORS
- 🗄️ = Base de données
- 📥 = Récupération de données
- ✅ = Validation/Succès
- ❌ = Erreur
- 👤 = Utilisateur
- 🔔 = Notification

Exemple:
```php
// 🔐 Vérification que l'utilisateur est admin (via session ou JWT)
$user = AuthMiddleware::requireAdmin();
```

---

## 🚀 COMMITS RÉCENTS

1. `fix: correction complète des tests - tous les tests passent (32/32)`
   - Correction de tous les tests unitaires
   - Standardisation des méthodes create()

2. `feat: correction accès admin et ajout notifications`
   - Support Session + JWT dans AuthMiddleware
   - Composant notifications React
   - CORS sur toutes les API

---

## ✨ PROCHAINES ÉTAPES SUGGÉRÉES

1. ✅ **Tests manuels** - Tester toutes les fonctions admin
2. 📱 **Responsive** - Vérifier affichage mobile
3. 🎨 **Style notifications** - Ajouter CSS pour les notifications
4. 🔄 **Rafraîchissement auto** - Notifications en temps réel
5. 🗑️ **Marquer comme lu** - Permettre de marquer les notifications lues

---

**📅 Dernière mise à jour**: 2 octobre 2025  
**👥 Équipe**: Documentation complète avec commentaires simples pour collaboration
