# 📚 Guide Débutant - Parc National

## 🎯 C'est quoi ce projet ?

Un site web pour gérer un parc national avec :
- **Connexion** : Les visiteurs et admins peuvent se connecter
- **Dashboard** : Tableau de bord avec météo et statistiques
- **Campings** : Liste et détails des zones de camping
- **Notifications** : Alertes pour les visiteurs

---

## 🏗️ Architecture Simple

```
📁 parcNational/
├── 📁 backend/        ← Serveur PHP (le cerveau)
│   ├── api/          ← Points d'entrée pour les données
│   ├── models/       ← Communication avec la base de données
│   └── utils/        ← Outils (sécurité, JWT, validation)
│
└── 📁 frontend/       ← Interface React (ce que l'utilisateur voit)
    └── src/
        ├── components/ ← Blocs réutilisables (boutons, formulaires...)
        ├── pages/      ← Pages complètes (Login, Dashboard...)
        └── hooks/      ← Fonctions pour communiquer avec l'API
```

---

## 🚀 Lancer le Projet

### 1. Démarrer le backend
```bash
cd c:\wamp64\www\parcNational\backend
php -S localhost:8080
```
✅ Le serveur démarre sur **http://localhost:8080**

### 2. Démarrer le frontend
```bash
cd c:\wamp64\www\parcNational\frontend
npm run dev
```
✅ L'interface s'ouvre sur **http://localhost:5173**

### 3. Se connecter
- **Visiteur** : `user@test.fr` / `password123`
- **Admin** : `admin@test.fr` / `admin123`

---

## 📂 Fichiers Importants

### Backend (Serveur)

#### `backend/api/camping.php` - API Camping Unifiée
**C'est quoi ?** Un fichier qui gère TOUTES les opérations camping :
- **GET** (lecture) : Accessible à tous
- **POST/PUT/DELETE** (création/modification/suppression) : Seulement les admins

**Comment ça marche ?**
```php
// 1. L'utilisateur fait une requête
GET http://localhost:8080/api/camping.php

// 2. Le fichier vérifie la méthode
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retourne la liste des campings
}

// 3. Si c'est POST/PUT/DELETE, il vérifie les permissions admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si l'utilisateur est admin
    // Crée un nouveau camping
}
```

**Pourquoi unifié ?**
- ✅ Un seul fichier à maintenir
- ✅ Logique centralisée
- ✅ Plus facile à débuguer

---

#### `backend/controllers/AuthController.php` - Connexion
**C'est quoi ?** Le contrôleur qui gère la connexion/déconnexion.

**Méthodes importantes :**
```php
// 1. Login - Connexion utilisateur
handleRequestLogin() 
// Vérifie email + mot de passe
// Crée une session PHP
// Retourne {loggedIn: true, user: {...}}

// 2. CheckSession - Vérifie si connecté
checkSession()
// Regarde si $_SESSION['user'] existe
// Retourne {loggedIn: true/false}

// 3. Logout - Déconnexion
handleRequestLogout()
// Détruit la session
// Retourne {loggedIn: false}
```

**Ordre important !**
```php
// 1. D'ABORD les en-têtes CORS (pour permettre frontend 5173 → backend 8080)
$this->setCorsHeaders();

// 2. ENSUITE démarrer la session
session_start();

// Sinon ça ne marche pas !
```

---

#### `backend/utils/BaseController.php` - Base Commune
**C'est quoi ?** Une classe parent pour tous les contrôleurs.

**Pourquoi ?**
```php
// Au lieu de répéter dans CHAQUE fichier :
header('Access-Control-Allow-Origin: ...');
header('Access-Control-Allow-Methods: ...');
// etc...

// On écrit UNE FOIS dans BaseController
class BaseController {
    public function setCorsHeaders() {
        // Détecte automatiquement l'origine (5173, 5174...)
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        header("Access-Control-Allow-Origin: $origin");
        // ...
    }
}

// Et les autres contrôleurs héritent
class AuthController extends BaseController {
    // Hérite automatiquement de setCorsHeaders()
}
```

---

### Frontend (Interface)

#### `frontend/src/App.jsx` - Point d'Entrée
**C'est quoi ?** Le fichier principal qui gère :
1. **La connexion** : Vérifie si l'utilisateur est connecté
2. **Les routes** : Affiche la bonne page selon l'URL
3. **La navigation** : Redirige après login/logout

**Flow de connexion :**
```jsx
// 1. Au chargement, vérifier si connecté
useEffect(() => {
  checkSession(); // Appelle backend/auth/profile.php
}, []);

// 2. Si connecté, afficher le dashboard
{isAuthenticated && user && <DashboardPage user={user} />}

// 3. Sinon, rediriger vers /login
{!isAuthenticated && <Navigate to="/login" replace />}
```

**Pourquoi un seul checkSession ?**
```jsx
// ❌ AVANT (infinite loop)
App.jsx : checkSession() → Dashboard mounted
DashboardPage.jsx : checkSession() → Re-render App.jsx
App.jsx : checkSession() → Dashboard mounted
// → Boucle infinie !

// ✅ MAINTENANT (simple)
App.jsx : checkSession() → Met à jour state
DashboardPage.jsx : Reçoit le prop `user` directement
// → Pas de boucle, 1 seul appel API
```

---

#### `frontend/src/components/Dashboard/MeteoWidget.jsx` - Météo
**C'est quoi ?** Un composant qui affiche la météo des Calanques de Marseille.

**Comment ça marche ?**
```jsx
// 1. Coordonnées GPS des Calanques
const LAT = 43.2094;
const LON = 5.4371;

// 2. Appel API Open-Meteo (gratuit)
useEffect(() => {
  fetch(`https://api.open-meteo.com/v1/forecast?latitude=${LAT}&longitude=${LON}&current=...&daily=...`)
    .then(res => res.json())
    .then(data => setWeatherData(data));
}, []);

// 3. Affichage
// - Conditions actuelles (temp, humidité, vent)
// - Prévisions 3 jours (min/max)
```

**Pourquoi Open-Meteo ?**
- ✅ Gratuit (pas besoin de clé API)
- ✅ Aucune limite de requêtes pour usage personnel
- ✅ Données précises pour la France

---

#### `frontend/src/hooks/useAPI.js` - Communication API
**C'est quoi ?** Des fonctions réutilisables pour appeler le backend.

**Exemple avec useCamping :**
```jsx
export function useCamping() {
  // Fonction pour récupérer tous les campings
  const getCampings = async () => {
    const response = await fetch('http://localhost:8080/api/camping.php');
    return await response.json();
  };

  // Fonction pour créer un camping
  const createCamping = async (data) => {
    const response = await fetch('http://localhost:8080/api/camping.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    return await response.json();
  };

  return { getCampings, createCamping };
}
```

**Utilisation dans un composant :**
```jsx
function CampingListPage() {
  const { getCampings } = useCamping();
  const [campings, setCampings] = useState([]);

  useEffect(() => {
    getCampings().then(data => setCampings(data));
  }, []);

  return (
    <ul>
      {campings.map(camping => <li key={camping.id}>{camping.nom}</li>)}
    </ul>
  );
}
```

**Pourquoi des hooks ?**
- ✅ **DRY** (Don't Repeat Yourself) : Écrire une fois, utiliser partout
- ✅ **Maintenance** : Changer l'URL à un seul endroit
- ✅ **Lisibilité** : `getCampings()` au lieu de `fetch('http://...')`

---

## 🔐 Sécurité Simplifiée

### Sessions PHP
```php
// backend/controllers/AuthController.php

// 1. Login réussi → Créer session
$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'role' => $user['role']
];

// 2. Vérifier si connecté
if (isset($_SESSION['user'])) {
    // Utilisateur connecté
}

// 3. Déconnexion → Détruire session
session_destroy();
```

### Credentials Include
```jsx
// frontend/src/hooks/useAPI.js

// Pour que les cookies de session soient envoyés
fetch('http://localhost:8080/api/...', {
  credentials: 'include'  // ← Important !
})
```

**Pourquoi ?**
- Sans `credentials: 'include'` : Le navigateur n'envoie PAS les cookies
- Le backend ne peut pas lire `$_SESSION['user']`
- L'utilisateur semble déconnecté même après login

---

## 🐛 Débogage pour Débutants

### 1. Le frontend ne se connecte pas au backend
**Symptôme :** Erreur CORS dans la console

**Solution :**
```bash
# Vérifier que le backend tourne bien sur :8080
netstat -ano | findstr :8080

# Redémarrer le backend
cd backend
php -S localhost:8080
```

---

### 2. Erreur "Undefined array key 'user'"
**Symptôme :** Erreur PHP dans `checkSession()`

**Cause :** Session non démarrée ou utilisateur non connecté

**Solution :**
```php
// Toujours vérifier AVANT d'accéder
if (isset($_SESSION['user'])) {
    return $_SESSION['user'];
} else {
    return ['loggedIn' => false];
}
```

---

### 3. Les changements ne s'affichent pas
**Frontend (React) :**
```bash
# Vite recharge automatiquement, mais parfois il faut forcer
npm run dev

# Ou vider le cache navigateur
Ctrl + Shift + R (Windows)
Cmd + Shift + R (Mac)
```

**Backend (PHP) :**
```bash
# Redémarrer le serveur
Ctrl + C (arrêter)
php -S localhost:8080 (relancer)
```

---

## 📊 Flow Complet d'une Requête

### Exemple : Afficher la liste des campings

```
1. L'utilisateur visite /campings
   └─→ React Router affiche <CampingListPage />

2. Le composant CampingListPage.jsx s'affiche
   └─→ useEffect(() => { getCampings() }, [])

3. getCampings() appelle l'API
   └─→ fetch('http://localhost:8080/api/camping.php')

4. Le backend reçoit la requête
   └─→ camping.php vérifie la méthode GET

5. Le backend interroge la base de données
   └─→ SELECT * FROM camping

6. Le backend retourne le JSON
   └─→ json_encode([$camping1, $camping2, ...])

7. Le frontend reçoit les données
   └─→ setCampings(data)

8. React affiche la liste
   └─→ {campings.map(c => <li>{c.nom}</li>)}
```

**Temps total :** ~100-200ms

---

## 🎨 Conseils pour Modifier le Code

### 1. Ajouter une nouvelle page
```bash
# Créer le composant
frontend/src/pages/NouvellePage.jsx

# Ajouter la route dans App.jsx
<Route path="/nouvelle" element={<NouvellePage />} />
```

### 2. Ajouter un champ à la base de données
```sql
-- 1. Modifier la table
ALTER TABLE camping ADD COLUMN description TEXT;

-- 2. Mettre à jour le modèle PHP
class Camping {
    public $description;
}

-- 3. Mettre à jour le formulaire React
<input name="description" value={form.description} onChange={handleChange} />
```

### 3. Ajouter une API endpoint
```php
// backend/api/nouvelle-ressource.php

<?php
require_once '../utils/BaseController.php';

class NouvelleRessourceController extends BaseController {
    public function __construct() {
        parent::__construct();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Logique GET
        }
    }
}

$controller = new NouvelleRessourceController();
$controller->handleRequest();
```

---

## 🚫 Erreurs Courantes à Éviter

### 1. Oublier CORS
```php
// ❌ Sans CORS
<?php
session_start(); // Bloqué par le navigateur !

// ✅ Avec CORS
<?php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
session_start();
```

---

### 2. Mélanger les URLs
```jsx
// ❌ Mélange WAMP et PHP server
fetch('/parcNational/backend/api/...')  // WAMP
fetch('http://localhost:8080/api/...')  // PHP server

// ✅ Toujours utiliser :8080
fetch('http://localhost:8080/api/...')
```

---

### 3. Double checkSession
```jsx
// ❌ Boucle infinie
function App() {
  useEffect(() => { checkSession() }, []);
  return <DashboardPage />; // ← Appelle checkSession() aussi
}

// ✅ Un seul appel
function App() {
  const [user, setUser] = useState(null);
  useEffect(() => { checkSession().then(setUser) }, []);
  return <DashboardPage user={user} />; // ← Reçoit le prop
}
```

---

## 📦 Résumé pour Débutants

| Concept | Explication Simple | Fichier Exemple |
|---------|-------------------|-----------------|
| **Backend** | Le serveur qui stocke et gère les données | `backend/api/camping.php` |
| **Frontend** | L'interface visuelle (ce que tu vois) | `frontend/src/pages/LoginPage.jsx` |
| **API** | Le pont entre frontend et backend | `backend/api/camping.php` |
| **CORS** | Permission pour que 5173 parle à 8080 | `backend/utils/BaseController.php` |
| **Session** | Mémoriser que l'utilisateur est connecté | `$_SESSION['user']` |
| **Hook** | Fonction réutilisable React | `frontend/src/hooks/useAPI.js` |
| **Component** | Bloc d'interface réutilisable | `frontend/src/components/Dashboard/MeteoWidget.jsx` |

---

## 🎯 Prochaines Étapes

1. ✅ **Comprendre l'architecture** (ce guide)
2. 🔄 **Tester les fonctionnalités** (login, dashboard, météo)
3. 🏕️ **Implémenter Camping** (liste, détails, création)
4. 🗺️ **Ajouter Leaflet** (cartes interactives)
5. 📊 **Dashboard avancé** (statistiques, graphiques)

---

**Dernière mise à jour :** ${new Date().toLocaleDateString('fr-FR')}
**Niveau :** Débutant
**Temps de lecture :** 30 minutes
