# 🔍 Explication Complète de `useAPI.js` - Pour Débutants

## 🎯 C'est quoi ce fichier ?

**useAPI.js** est le **traducteur** entre votre interface React et votre serveur PHP.

```
┌─────────────┐                  ┌─────────────┐
│  Frontend   │  ←─ useAPI.js ─→ │  Backend    │
│  (React)    │                  │  (PHP)      │
│  Port 5173  │                  │  Port 8080  │
└─────────────┘                  └─────────────┘
```

**Sans useAPI.js :** Vous devriez écrire `fetch('http://localhost:8080/...')` partout dans votre code 😰

**Avec useAPI.js :** Vous écrivez simplement `login(email, password)` 😊

---

## 📖 Ligne par Ligne - Partie 1 : Imports

```javascript
import { useState, useCallback } from 'react';
```

### Qu'est-ce que c'est ?
- **`useState`** : Une fonction React pour créer des variables qui peuvent changer
- **`useCallback`** : Une fonction React pour mémoriser des fonctions (optimisation)

### Exemple simple :
```javascript
// useState - créer une variable qui change
const [age, setAge] = useState(25);
// age = 25 (valeur actuelle)
// setAge(30) → change age à 30

// useCallback - mémoriser une fonction
const addition = useCallback(() => {
  return 2 + 2;
}, []); // Cette fonction ne sera créée qu'une seule fois
```

---

## 🌐 Partie 2 : Configuration de Base

```javascript
const API_BASE = 'http://localhost:8080';
```

### Qu'est-ce que c'est ?
L'**adresse de votre serveur backend**.

### Pourquoi une variable ?
```javascript
// ❌ MAUVAIS (répété partout)
fetch('http://localhost:8080/api/camping.php')
fetch('http://localhost:8080/api/sentiers.php')
fetch('http://localhost:8080/controllers/AuthController.php')

// ✅ BON (changement à un seul endroit)
const API_BASE = 'http://localhost:8080';
fetch(`${API_BASE}/api/camping.php`)
fetch(`${API_BASE}/api/sentiers.php`)
fetch(`${API_BASE}/controllers/AuthController.php`)

// Si vous changez de serveur, vous modifiez UNE SEULE ligne !
```

### Décomposition :
- **`http://`** : Protocole (langage de communication web)
- **`localhost`** : Votre ordinateur (vous-même)
- **`8080`** : Numéro de porte (port) où le serveur PHP écoute

---

## 🔧 Partie 3 : Hook Principal `useAPI()`

```javascript
export function useAPI() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
```

### 📌 Variable 1 : `loading`

**C'est quoi ?**
Un indicateur pour savoir si une requête est en cours.

**États possibles :**
- `loading = false` → Rien en cours
- `loading = true` → En train de charger...

**Utilisation dans un composant :**
```javascript
function MessCampings() {
  const { loading, getAll } = useCamping();
  
  if (loading) {
    return <p>Chargement...</p>; // ← Affiché pendant la requête
  }
  
  return <p>Campings chargés !</p>;
}
```

---

### 📌 Variable 2 : `error`

**C'est quoi ?**
Un message d'erreur si quelque chose ne marche pas.

**États possibles :**
- `error = null` → Tout va bien ✅
- `error = "Message d'erreur"` → Problème ❌

**Utilisation dans un composant :**
```javascript
function MessCampings() {
  const { error, getAll } = useCamping();
  
  if (error) {
    return <p style={{color: 'red'}}>Erreur : {error}</p>;
  }
  
  return <p>Tout va bien !</p>;
}
```

---

## 🚀 Partie 4 : Fonction `request()`

```javascript
const request = useCallback(async (endpoint, options = {}) => {
  setLoading(true);
  setError(null);
```

### Décomposition Ligne par Ligne

#### Ligne 1 : `const request = useCallback(async (endpoint, options = {}) => {`

**Décomposition :**
1. **`const request =`** → Créer une fonction nommée `request`
2. **`useCallback(`** → Mémoriser cette fonction (optimisation React)
3. **`async`** → Fonction asynchrone (peut attendre des réponses)
4. **`(endpoint, options = {})`** → Paramètres :
   - `endpoint` : Le chemin de l'API (ex: `/api/camping.php`)
   - `options = {}` : Options supplémentaires (méthode GET/POST, headers...)

**Exemple :**
```javascript
// Appel simple
request('/api/camping.php', { method: 'GET' })

// Équivalent à
request(
  endpoint = '/api/camping.php',
  options = { method: 'GET' }
)
```

---

#### Ligne 2-3 : Réinitialisation

```javascript
setLoading(true);   // ← Active le mode "chargement"
setError(null);     // ← Efface les anciennes erreurs
```

**Analogie :**
C'est comme allumer une lumière "En cours..." et effacer le tableau des erreurs avant de commencer.

```
Avant la requête :
┌──────────────┐
│ Loading: ❌  │
│ Error: ❌    │
└──────────────┘

Pendant la requête :
┌──────────────┐
│ Loading: ✅  │  ← setLoading(true)
│ Error: ❌    │  ← setError(null)
└──────────────┘
```

---

#### Ligne 4-14 : Le `try` (Tentative)

```javascript
try {
  const response = await fetch(`${API_BASE}${endpoint}`, {
    credentials: 'include',
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
    },
    ...options,
  });
```

### 🔍 Décomposition de `fetch()`

**`fetch()`** = Envoyer une requête HTTP (demander des données au serveur)

**Paramètre 1 : URL complète**
```javascript
`${API_BASE}${endpoint}`
// Si API_BASE = 'http://localhost:8080'
// Et endpoint = '/api/camping.php'
// → 'http://localhost:8080/api/camping.php'
```

**Paramètre 2 : Options de la requête**

##### Option 1 : `credentials: 'include'`
```javascript
credentials: 'include'
```

**C'est quoi ?**
Dit au navigateur d'envoyer les **cookies de session** avec la requête.

**Pourquoi important ?**
```php
// Backend PHP (AuthController.php)
session_start();
$_SESSION['user'] = [...]; // ← Créé un cookie

// Frontend (sans credentials)
fetch('http://localhost:8080/api/...') 
// ❌ Le backend ne voit PAS $_SESSION['user']

// Frontend (avec credentials)
fetch('http://localhost:8080/api/...', {
  credentials: 'include'
})
// ✅ Le backend voit $_SESSION['user']
```

**Analogie :**
C'est comme montrer votre **carte d'identité** au serveur pour qu'il sache qui vous êtes.

---

##### Option 2 : `headers`
```javascript
headers: {
  'Content-Type': 'application/json',
  ...options.headers,
}
```

**C'est quoi ?**
Des **métadonnées** (informations supplémentaires) envoyées avec la requête.

**`Content-Type: 'application/json'`**
Dit au serveur : "Je t'envoie des données au format JSON"

**Exemple :**
```javascript
// Requête POST avec JSON
fetch('/api/camping.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'  // ← Format des données
  },
  body: JSON.stringify({
    nom: 'Camping des Calanques',
    capacite: 50
  })
})

// Le serveur reçoit :
// $_POST (vide) ❌
// file_get_contents('php://input') → '{"nom":"Camping des Calanques","capacite":50}' ✅
```

**`...options.headers`**
Le spread operator `...` copie les headers personnalisés.

```javascript
// Exemple avec header personnalisé
request('/api/camping.php', {
  headers: {
    'Authorization': 'Bearer token123'
  }
})

// Headers finaux envoyés :
{
  'Content-Type': 'application/json',  // ← Header par défaut
  'Authorization': 'Bearer token123'   // ← Header personnalisé
}
```

---

##### Option 3 : `...options`
```javascript
...options
```

**C'est quoi ?**
Copie **toutes les autres options** passées en paramètre.

**Exemple :**
```javascript
request('/api/camping.php', {
  method: 'POST',
  body: JSON.stringify({ nom: 'Camping' })
})

// Options finales :
{
  credentials: 'include',           // ← Ajouté par défaut
  headers: {
    'Content-Type': 'application/json'
  },
  method: 'POST',                   // ← Copié depuis options
  body: '{"nom":"Camping"}'         // ← Copié depuis options
}
```

---

#### Ligne 15-17 : Récupération des Données

```javascript
const data = await response.json();
```

**Décomposition :**
1. **`response`** : La réponse brute du serveur (headers, status, body...)
2. **`.json()`** : Convertit le corps de la réponse en objet JavaScript
3. **`await`** : Attend que la conversion soit terminée

**Exemple :**
```javascript
// Serveur PHP envoie :
echo json_encode(['nom' => 'Camping des Calanques', 'capacite' => 50]);

// Frontend reçoit (response brute) :
// '{"nom":"Camping des Calanques","capacite":50}'

// Après response.json() :
const data = {
  nom: 'Camping des Calanques',
  capacite: 50
};

// Maintenant on peut faire :
console.log(data.nom); // → 'Camping des Calanques'
```

---

#### Ligne 18-20 : Gestion des Erreurs

```javascript
if (!response.ok || data.success === false) {
  throw new Error(data.error || 'Une erreur est survenue');
}
```

**Décomposition :**

**Condition 1 : `!response.ok`**
```javascript
// response.ok = true si status HTTP 200-299 (succès)
// response.ok = false si status HTTP 400-599 (erreur)

// Exemple :
// Status 200 → response.ok = true  ✅
// Status 404 → response.ok = false ❌
// Status 500 → response.ok = false ❌
```

**Condition 2 : `data.success === false`**
```javascript
// Backend PHP peut retourner :
{
  "success": false,
  "error": "Camping introuvable"
}

// On vérifie si le backend dit explicitement que c'est un échec
```

**`throw new Error(...)`**
```javascript
// Créer une erreur et arrêter l'exécution
// → Passe directement au bloc catch

throw new Error('Message');
// Équivalent à "lancer une exception" en PHP
```

**`data.error || 'Une erreur est survenue'`**
```javascript
// Opérateur OR (||)
// Si data.error existe → utiliser data.error
// Sinon → utiliser 'Une erreur est survenue'

// Exemple :
const data = { error: 'Camping introuvable' };
const message = data.error || 'Une erreur est survenue';
// → message = 'Camping introuvable'

const data2 = {};
const message2 = data2.error || 'Une erreur est survenue';
// → message2 = 'Une erreur est survenue'
```

---

#### Ligne 21-23 : Fin du Try

```javascript
setLoading(false);
return data;
```

**Explication :**
1. **`setLoading(false)`** : Désactive le mode "chargement" (requête terminée)
2. **`return data`** : Retourne les données au composant qui a appelé la fonction

**Flow complet :**
```
1. setLoading(true)    → Affiche "Chargement..."
2. fetch(...)          → Envoie la requête
3. await response      → Attend la réponse
4. await response.json() → Convertit en objet
5. Vérification erreur → Si erreur → throw
6. setLoading(false)   → Cache "Chargement..."
7. return data         → Renvoie les données au composant
```

---

#### Ligne 24-28 : Le `catch` (Attrape les Erreurs)

```javascript
} catch (err) {
  setError(err.message);
  setLoading(false);
  throw err;
}
```

**Décomposition :**

**Ligne 1 : `catch (err)`**
- Attrape **toutes les erreurs** du bloc `try`
- `err` = L'objet erreur avec `.message`, `.name`, etc.

**Ligne 2 : `setError(err.message)`**
- Enregistre le message d'erreur dans le state
- Permet d'afficher l'erreur dans l'interface

**Ligne 3 : `setLoading(false)`**
- Désactive le chargement (même en cas d'erreur)

**Ligne 4 : `throw err`**
- Re-lance l'erreur pour que le composant qui appelle puisse aussi la gérer

**Exemple complet :**
```javascript
// Dans un composant
function MesCampings() {
  const { error, loading, getAll } = useCamping();
  const [campings, setCampings] = useState([]);

  useEffect(() => {
    getAll()
      .then(data => setCampings(data))
      .catch(err => {
        // Erreur attrapée ici aussi !
        console.error('Impossible de charger les campings', err);
      });
  }, []);

  if (loading) return <p>Chargement...</p>;
  if (error) return <p>Erreur : {error}</p>; // ← setError() fait afficher ça

  return (
    <ul>
      {campings.map(c => <li key={c.id}>{c.nom}</li>)}
    </ul>
  );
}
```

---

## 🎯 Partie 5 : Fonctions de Raccourci (GET, POST, PUT, DELETE)

```javascript
const get = useCallback((endpoint) => {
  return request(endpoint, { method: 'GET' });
}, [request]);
```

### 📌 Fonction `get()`

**C'est quoi ?**
Un **raccourci** pour faire des requêtes GET (lecture de données).

**Équivalent :**
```javascript
// Sans raccourci
request('/api/camping.php', { method: 'GET' })

// Avec raccourci
get('/api/camping.php')

// Les deux font EXACTEMENT la même chose !
```

**Quand utiliser GET ?**
- Lire une liste de campings
- Récupérer les détails d'un camping
- Charger les notifications

**Règle :** GET ne modifie JAMAIS les données (lecture seule)

---

### 📌 Fonction `post()`

```javascript
const post = useCallback((endpoint, body) => {
  return request(endpoint, {
    method: 'POST',
    body: JSON.stringify(body),
  });
}, [request]);
```

**C'est quoi ?**
Un **raccourci** pour **créer** de nouvelles données.

**Paramètres :**
- `endpoint` : Chemin de l'API
- `body` : Les données à envoyer (objet JavaScript)

**Exemple :**
```javascript
// Créer un nouveau camping
post('/api/camping.php', {
  nom: 'Camping des Calanques',
  localisation: 'Marseille',
  capacite: 50
})

// Le backend PHP reçoit :
// file_get_contents('php://input') → '{"nom":"Camping des Calanques",...}'
```

**`JSON.stringify(body)`**
```javascript
// Convertit un objet JavaScript en chaîne JSON

// Avant
const body = { nom: 'Camping', capacite: 50 };

// Après JSON.stringify()
'{"nom":"Camping","capacite":50}'

// Pourquoi ? Le réseau ne transmet que du TEXTE, pas des objets !
```

---

### 📌 Fonction `put()`

```javascript
const put = useCallback((endpoint, body) => {
  return request(endpoint, {
    method: 'PUT',
    body: JSON.stringify(body),
  });
}, [request]);
```

**C'est quoi ?**
Un **raccourci** pour **modifier** des données existantes.

**Différence avec POST :**
- **POST** : Créer une nouvelle ressource
- **PUT** : Modifier une ressource existante

**Exemple :**
```javascript
// Modifier le camping ID 5
put('/api/camping.php', {
  id: 5,
  nom: 'Camping Renommé',
  capacite: 60
})

// Backend PHP :
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id']; // 5
    // UPDATE camping SET nom='...', capacite=60 WHERE id=5
}
```

---

### 📌 Fonction `del()` (DELETE)

```javascript
const del = useCallback((endpoint, body) => {
  return request(endpoint, {
    method: 'DELETE',
    body: JSON.stringify(body),
  });
}, [request]);
```

**C'est quoi ?**
Un **raccourci** pour **supprimer** des données.

**Pourquoi `del` et pas `delete` ?**
`delete` est un mot réservé en JavaScript (interdit comme nom de variable).

**Exemple :**
```javascript
// Supprimer le camping ID 5
del('/api/camping.php', { id: 5 })

// Backend PHP :
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id']; // 5
    // DELETE FROM camping WHERE id=5
}
```

---

### 📌 Retour de `useAPI()`

```javascript
return { loading, error, get, post, put, del };
```

**C'est quoi ?**
Retourne **tout ce dont un composant a besoin** pour communiquer avec l'API.

**Utilisation :**
```javascript
function MonComposant() {
  const { loading, error, get, post } = useAPI();
  
  // Maintenant on peut utiliser :
  // - loading : pour afficher "Chargement..."
  // - error : pour afficher les erreurs
  // - get, post, put, del : pour faire des requêtes
}
```

---

## 🔐 Partie 6 : Hook `useAuth()` (Authentification)

```javascript
export function useAuth() {
  const { post, get } = useAPI();
```

**C'est quoi ?**
Un hook **spécialisé** pour toutes les opérations d'authentification.

**Pourquoi un hook séparé ?**
```javascript
// ❌ Sans hook spécialisé (répétitif)
function LoginPage() {
  const { post } = useAPI();
  
  const handleLogin = () => {
    post('/controllers/AuthController.php?action=login', {
      email,
      mot_de_passe
    });
  };
}

// ✅ Avec hook spécialisé (simple)
function LoginPage() {
  const { login } = useAuth();
  
  const handleLogin = () => {
    login(email, mot_de_passe); // ← Plus court et clair !
  };
}
```

---

### 📌 Fonction `login()`

```javascript
const login = useCallback(async (email, mot_de_passe) => {
  return await post('/controllers/AuthController.php?action=login', {
    email,
    mot_de_passe,
  });
}, [post]);
```

**Décomposition :**

**Paramètres :**
- `email` : Email de l'utilisateur (ex: `user@test.fr`)
- `mot_de_passe` : Mot de passe (ex: `password123`)

**Ce qui se passe :**
```javascript
// Appel
login('user@test.fr', 'password123')

// Équivalent à
post('/controllers/AuthController.php?action=login', {
  email: 'user@test.fr',
  mot_de_passe: 'password123'
})

// Envoie une requête POST au backend :
POST http://localhost:8080/controllers/AuthController.php?action=login
Body: {"email":"user@test.fr","mot_de_passe":"password123"}

// Backend PHP reçoit :
$_GET['action'] = 'login'
file_get_contents('php://input') = '{"email":"...","mot_de_passe":"..."}'
```

**Réponse du backend :**
```json
{
  "loggedIn": true,
  "user": {
    "id": 1,
    "email": "user@test.fr",
    "nom": "John Doe",
    "role": "visiteur"
  }
}
```

---

### 📌 Fonction `register()`

```javascript
const register = useCallback(async (nom, email, mot_de_passe) => {
  return await post('/controllers/AuthController.php?action=register', {
    nom,
    email,
    mot_de_passe,
  });
}, [post]);
```

**C'est quoi ?**
Créer un nouveau compte utilisateur.

**Paramètres :**
- `nom` : Nom complet (ex: `John Doe`)
- `email` : Email (ex: `john@test.fr`)
- `mot_de_passe` : Mot de passe (ex: `secret123`)

**Exemple d'utilisation :**
```javascript
function RegisterPage() {
  const { register } = useAuth();
  const [form, setForm] = useState({ nom: '', email: '', mot_de_passe: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      const result = await register(form.nom, form.email, form.mot_de_passe);
      
      if (result.success) {
        alert('Compte créé !');
        navigate('/login');
      }
    } catch (err) {
      alert('Erreur : ' + err.message);
    }
  };

  return <form onSubmit={handleSubmit}>...</form>;
}
```

---

### 📌 Fonction `logout()`

```javascript
const logout = useCallback(async () => {
  return await post('/controllers/AuthController.php?action=logout');
}, [post]);
```

**C'est quoi ?**
Déconnecter l'utilisateur (détruire la session).

**Exemple d'utilisation :**
```javascript
function DashboardPage() {
  const { logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await logout();
      navigate('/login'); // Rediriger vers la page de connexion
    } catch (err) {
      alert('Erreur lors de la déconnexion');
    }
  };

  return (
    <div>
      <button onClick={handleLogout}>Se déconnecter</button>
    </div>
  );
}
```

**Backend PHP :**
```php
// AuthController.php
public function handleRequestLogout() {
    session_start();
    session_destroy(); // ← Détruit la session
    
    echo json_encode(['loggedIn' => false]);
}
```

---

### 📌 Fonction `checkSession()`

```javascript
const checkSession = useCallback(async () => {
  return await get('/controllers/AuthController.php?action=check');
}, [get]);
```

**C'est quoi ?**
Vérifier si l'utilisateur est **actuellement connecté**.

**Pourquoi important ?**
```javascript
// App.jsx - Vérifie au chargement de la page
function App() {
  const { checkSession } = useAuth();
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkSession()
      .then(data => {
        if (data.loggedIn) {
          setUser(data.user); // Utilisateur connecté
        }
      })
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Vérification...</p>;

  // Rediriger selon l'état de connexion
  return user ? <DashboardPage user={user} /> : <Navigate to="/login" />;
}
```

**Réponse du backend :**
```json
// Si connecté
{
  "loggedIn": true,
  "user": {
    "id": 1,
    "email": "user@test.fr",
    "nom": "John Doe",
    "role": "visiteur"
  }
}

// Si NON connecté
{
  "loggedIn": false
}
```

---

### 📌 Retour de `useAuth()`

```javascript
return { login, register, logout, checkSession };
```

**Utilisation complète :**
```javascript
function AuthExample() {
  const { login, register, logout, checkSession } = useAuth();

  // Connexion
  const handleLogin = () => {
    login('user@test.fr', 'password123');
  };

  // Inscription
  const handleRegister = () => {
    register('John Doe', 'john@test.fr', 'password123');
  };

  // Déconnexion
  const handleLogout = () => {
    logout();
  };

  // Vérification
  const handleCheck = () => {
    checkSession().then(data => console.log(data));
  };
}
```

---

## 🏕️ Partie 7 : Hook `useCamping()`

```javascript
export function useCamping() {
  const { get, post, put, del } = useAPI();
```

**C'est quoi ?**
Un hook **spécialisé** pour gérer les campings (CRUD complet).

**CRUD = Create, Read, Update, Delete**

---

### 📌 Fonction `getAll()` - Lire Tous les Campings

```javascript
const getAll = useCallback(async () => {
  const result = await get('/api/camping.php');
  return result.data || result;
}, [get]);
```

**Décomposition :**

**`await get('/api/camping.php')`**
- Envoie une requête GET au backend
- Backend retourne la liste de tous les campings

**`return result.data || result`**
```javascript
// Gère 2 formats de réponse possibles

// Format 1 (avec wrapper)
{
  "success": true,
  "data": [
    { id: 1, nom: 'Camping 1' },
    { id: 2, nom: 'Camping 2' }
  ]
}
// → Retourne result.data (le tableau)

// Format 2 (direct)
[
  { id: 1, nom: 'Camping 1' },
  { id: 2, nom: 'Camping 2' }
]
// → Retourne result (le tableau directement)
```

**Exemple d'utilisation :**
```javascript
function CampingListPage() {
  const { getAll } = useCamping();
  const [campings, setCampings] = useState([]);

  useEffect(() => {
    getAll().then(data => {
      setCampings(data); // [{ id: 1, nom: 'Camping 1' }, ...]
    });
  }, []);

  return (
    <ul>
      {campings.map(camping => (
        <li key={camping.id}>{camping.nom}</li>
      ))}
    </ul>
  );
}
```

---

### 📌 Fonction `getById()` - Lire UN Camping

```javascript
const getById = useCallback(async (id) => {
  const result = await get(`/api/camping.php?id=${id}`);
  return result.data || result;
}, [get]);
```

**Décomposition :**

**Paramètre : `id`**
- L'identifiant unique du camping (ex: `5`)

**`` `/api/camping.php?id=${id}` ``**
```javascript
// Template literal (backticks)
const id = 5;
`/api/camping.php?id=${id}`
// → '/api/camping.php?id=5'

// Équivalent à
'/api/camping.php?id=' + id
```

**Exemple d'utilisation :**
```javascript
function CampingDetailPage() {
  const { id } = useParams(); // Récupère l'ID depuis l'URL
  const { getById } = useCamping();
  const [camping, setCamping] = useState(null);

  useEffect(() => {
    getById(id).then(data => {
      setCamping(data); // { id: 5, nom: 'Camping X', capacite: 50 }
    });
  }, [id]);

  if (!camping) return <p>Chargement...</p>;

  return (
    <div>
      <h1>{camping.nom}</h1>
      <p>Capacité : {camping.capacite} personnes</p>
      <p>Localisation : {camping.localisation}</p>
    </div>
  );
}
```

---

### 📌 Fonction `create()` - Créer un Camping

```javascript
const create = useCallback(async (camping) => {
  const result = await post('/api/camping.php', camping);
  return result.data || result;
}, [post]);
```

**Décomposition :**

**Paramètre : `camping`**
- Un objet avec les données du nouveau camping

**Exemple d'utilisation :**
```javascript
function CampingCreatePage() {
  const { create } = useCamping();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    nom: '',
    localisation: '',
    capacite: 0
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      // Envoie les données au backend
      const result = await create({
        nom: form.nom,
        localisation: form.localisation,
        capacite: parseInt(form.capacite)
      });
      
      alert('Camping créé !');
      navigate('/campings'); // Retour à la liste
    } catch (err) {
      alert('Erreur : ' + err.message);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <input 
        name="nom" 
        value={form.nom} 
        onChange={e => setForm({...form, nom: e.target.value})}
        placeholder="Nom du camping"
      />
      <input 
        name="localisation" 
        value={form.localisation} 
        onChange={e => setForm({...form, localisation: e.target.value})}
        placeholder="Localisation"
      />
      <input 
        name="capacite" 
        type="number"
        value={form.capacite} 
        onChange={e => setForm({...form, capacite: e.target.value})}
        placeholder="Capacité"
      />
      <button type="submit">Créer</button>
    </form>
  );
}
```

**Backend PHP reçoit :**
```
POST http://localhost:8080/api/camping.php
Body: {
  "nom": "Camping des Calanques",
  "localisation": "Marseille",
  "capacite": 50
}
```

---

### 📌 Fonction `update()` - Modifier un Camping

```javascript
const update = useCallback(async (camping) => {
  const result = await put('/api/camping.php', camping);
  return result.data || result;
}, [put]);
```

**Différence avec `create()` :**
- **`create()`** : POST (nouveau camping sans ID)
- **`update()`** : PUT (camping existant avec ID)

**Exemple d'utilisation :**
```javascript
function CampingEditPage() {
  const { id } = useParams();
  const { getById, update } = useCamping();
  const navigate = useNavigate();
  const [form, setForm] = useState(null);

  // 1. Charger les données existantes
  useEffect(() => {
    getById(id).then(data => setForm(data));
  }, [id]);

  // 2. Sauvegarder les modifications
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      await update({
        id: parseInt(id),           // ← ID obligatoire pour PUT
        nom: form.nom,
        localisation: form.localisation,
        capacite: parseInt(form.capacite)
      });
      
      alert('Camping modifié !');
      navigate('/campings');
    } catch (err) {
      alert('Erreur : ' + err.message);
    }
  };

  if (!form) return <p>Chargement...</p>;

  return (
    <form onSubmit={handleSubmit}>
      <input 
        value={form.nom} 
        onChange={e => setForm({...form, nom: e.target.value})}
      />
      {/* ... autres champs ... */}
      <button type="submit">Modifier</button>
    </form>
  );
}
```

---

### 📌 Fonction `remove()` - Supprimer un Camping

```javascript
const remove = useCallback(async (id) => {
  const result = await del('/api/camping.php', { id });
  return result.data || result;
}, [del]);
```

**Décomposition :**

**Paramètre : `id`**
- L'ID du camping à supprimer

**`del('/api/camping.php', { id })`**
```javascript
// Syntaxe courte
{ id }

// Équivalent à
{ id: id }

// Si id = 5, alors
{ id: 5 }
```

**Exemple d'utilisation :**
```javascript
function CampingDetailPage() {
  const { id } = useParams();
  const { getById, remove } = useCamping();
  const navigate = useNavigate();
  const [camping, setCamping] = useState(null);

  useEffect(() => {
    getById(id).then(data => setCamping(data));
  }, [id]);

  const handleDelete = async () => {
    // Confirmation avant suppression
    if (!window.confirm(`Supprimer ${camping.nom} ?`)) {
      return;
    }

    try {
      await remove(id);
      alert('Camping supprimé !');
      navigate('/campings');
    } catch (err) {
      alert('Erreur : ' + err.message);
    }
  };

  if (!camping) return <p>Chargement...</p>;

  return (
    <div>
      <h1>{camping.nom}</h1>
      <p>Capacité : {camping.capacite}</p>
      <button onClick={handleDelete} style={{color: 'red'}}>
        🗑️ Supprimer
      </button>
    </div>
  );
}
```

---

### 📌 Retour de `useCamping()`

```javascript
return { getAll, getById, create, update, remove };
```

**Résumé des opérations :**

| Fonction | Méthode HTTP | Action | Exemple |
|----------|--------------|--------|---------|
| `getAll()` | GET | Lire tous les campings | `getAll()` |
| `getById(id)` | GET | Lire un camping spécifique | `getById(5)` |
| `create(camping)` | POST | Créer un nouveau camping | `create({ nom: 'X', capacite: 50 })` |
| `update(camping)` | PUT | Modifier un camping existant | `update({ id: 5, nom: 'Y' })` |
| `remove(id)` | DELETE | Supprimer un camping | `remove(5)` |

---

## 🥾 Partie 8 : Hook `useSentier()` (Sentiers)

```javascript
export function useSentier() {
  const { get, post, put, del } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/public/sentiers.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/public/sentiers.php?id=${id}`);
    return result.data || result;
  }, [get]);

  return { getAll, getById };
}
```

**Différence avec `useCamping()` :**
- Pas de `create`, `update`, `remove` (lecture seule pour les visiteurs)
- Endpoint différent : `/api/public/sentiers.php`

**Utilisation :**
```javascript
function SentiersPage() {
  const { getAll } = useSentier();
  const [sentiers, setSentiers] = useState([]);

  useEffect(() => {
    getAll().then(data => setSentiers(data));
  }, []);

  return (
    <div>
      <h1>Sentiers du Parc</h1>
      {sentiers.map(sentier => (
        <div key={sentier.id}>
          <h2>{sentier.nom}</h2>
          <p>Difficulté : {sentier.difficulte}</p>
          <p>Distance : {sentier.distance} km</p>
        </div>
      ))}
    </div>
  );
}
```

---

## 🔔 Partie 9 : Hook `useNotifications()`

```javascript
export function useNotifications() {
  const { get, post, del } = useAPI();

  const getActive = useCallback(async (limit = 10) => {
    const result = await get(`/api/notifications.php?action=list&limit=${limit}`);
    return result.data || result;
  }, [get]);

  const generate = useCallback(async () => {
    const result = await get('/api/notifications.php?action=generate');
    return result.data || result;
  }, [get]);

  const remove = useCallback(async (id) => {
    const result = await del('/api/notifications.php?action=delete', { id });
    return result.data || result;
  }, [del]);

  return { getActive, generate, remove };
}
```

### 📌 Fonction `getActive(limit)`

**Paramètre : `limit` (optionnel, défaut = 10)**
```javascript
// Avec limite par défaut
getActive() // → Récupère 10 notifications

// Avec limite personnalisée
getActive(5)  // → Récupère 5 notifications
getActive(20) // → Récupère 20 notifications
```

**Exemple d'utilisation :**
```javascript
function NotificationsWidget() {
  const { getActive } = useNotifications();
  const [notifications, setNotifications] = useState([]);

  useEffect(() => {
    // Charger les 5 dernières notifications
    getActive(5).then(data => setNotifications(data));
  }, []);

  return (
    <div>
      <h3>🔔 Notifications</h3>
      {notifications.map(notif => (
        <div key={notif.id} className="notification">
          <p>{notif.message}</p>
          <small>{notif.date}</small>
        </div>
      ))}
    </div>
  );
}
```

---

### 📌 Fonction `generate()`

**C'est quoi ?**
Déclenche la génération de **nouvelles notifications** basées sur les données du système.

**Exemple d'utilisation :**
```javascript
function AdminDashboard() {
  const { generate } = useNotifications();

  const handleGenerateNotifications = async () => {
    try {
      const result = await generate();
      alert(`${result.count} notifications générées !`);
    } catch (err) {
      alert('Erreur lors de la génération');
    }
  };

  return (
    <button onClick={handleGenerateNotifications}>
      🔄 Générer notifications
    </button>
  );
}
```

**Backend PHP (Notification.php) :**
```php
// Vérifie les sentiers dangereux
public function checkDangerousSentiers() {
    // SELECT * FROM sentiers WHERE niveau_danger > 3
    // Pour chaque sentier dangereux, créer une notification
}

// Vérifie la capacité des campings
public function checkCampingCapacity() {
    // SELECT * FROM camping WHERE occupancy > 90%
    // Créer une alerte si presque plein
}
```

---

### 📌 Fonction `remove(id)`

**C'est quoi ?**
Supprimer une notification (l'utilisateur l'a lue).

**Exemple d'utilisation :**
```javascript
function NotificationItem({ notification }) {
  const { remove } = useNotifications();

  const handleDismiss = async () => {
    try {
      await remove(notification.id);
      // Notification supprimée !
    } catch (err) {
      alert('Erreur lors de la suppression');
    }
  };

  return (
    <div className="notification">
      <p>{notification.message}</p>
      <button onClick={handleDismiss}>✕ Masquer</button>
    </div>
  );
}
```

---

## 🌿 Partie 10 : Hook `useRessources()`

```javascript
export function useRessources() {
  const { get } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/public/ressources_naturelles.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/public/ressources_naturelles.php?id=${id}`);
    return result.data || result;
  }, [get]);

  return { getAll, getById };
}
```

**C'est quoi ?**
Gestion des ressources naturelles du parc (faune, flore, points d'eau...).

**Exemple d'utilisation :**
```javascript
function RessourcesPage() {
  const { getAll } = useRessources();
  const [ressources, setRessources] = useState([]);

  useEffect(() => {
    getAll().then(data => setRessources(data));
  }, []);

  return (
    <div>
      <h1>🌿 Ressources Naturelles</h1>
      {ressources.map(ressource => (
        <div key={ressource.id}>
          <h2>{ressource.nom}</h2>
          <p>Type : {ressource.type}</p>
          <p>Localisation : {ressource.localisation}</p>
          <p>État : {ressource.etat_conservation}</p>
        </div>
      ))}
    </div>
  );
}
```

---

## 📊 Récapitulatif Global

### Structure du Fichier

```
useAPI.js
│
├── 🔧 useAPI()                  Hook de base
│   ├── loading                 État de chargement
│   ├── error                   Message d'erreur
│   ├── request()               Fonction principale fetch
│   ├── get()                   Raccourci GET
│   ├── post()                  Raccourci POST
│   ├── put()                   Raccourci PUT
│   └── del()                   Raccourci DELETE
│
├── 🔐 useAuth()                 Authentification
│   ├── login()                 Connexion
│   ├── register()              Inscription
│   ├── logout()                Déconnexion
│   └── checkSession()          Vérification session
│
├── 🏕️ useCamping()              Gestion campings
│   ├── getAll()                Liste complète
│   ├── getById()               Détails d'un camping
│   ├── create()                Nouveau camping
│   ├── update()                Modification
│   └── remove()                Suppression
│
├── 🥾 useSentier()              Gestion sentiers
│   ├── getAll()                Liste sentiers
│   └── getById()               Détails sentier
│
├── 🔔 useNotifications()        Notifications
│   ├── getActive()             Notifications actives
│   ├── generate()              Générer nouvelles
│   └── remove()                Supprimer notification
│
└── 🌿 useRessources()           Ressources naturelles
    ├── getAll()                Liste ressources
    └── getById()               Détails ressource
```

---

## 🎯 Comment Utiliser ce Fichier ?

### Exemple Complet : Page de Liste de Campings

```javascript
import { useState, useEffect } from 'react';
import { useCamping } from '../hooks/useAPI';

function CampingListPage() {
  // 1. Importer le hook
  const { getAll, loading, error } = useCamping();
  
  // 2. État local pour stocker les données
  const [campings, setCampings] = useState([]);
  
  // 3. Charger les données au montage du composant
  useEffect(() => {
    getAll()
      .then(data => {
        setCampings(data);
      })
      .catch(err => {
        console.error('Erreur:', err);
      });
  }, []); // [] = exécuter une seule fois
  
  // 4. Affichage conditionnel
  if (loading) {
    return <div>⏳ Chargement des campings...</div>;
  }
  
  if (error) {
    return <div style={{color: 'red'}}>❌ Erreur : {error}</div>;
  }
  
  // 5. Affichage des données
  return (
    <div>
      <h1>🏕️ Liste des Campings</h1>
      <ul>
        {campings.map(camping => (
          <li key={camping.id}>
            <strong>{camping.nom}</strong>
            <br />
            📍 {camping.localisation}
            <br />
            👥 Capacité : {camping.capacite} personnes
          </li>
        ))}
      </ul>
    </div>
  );
}

export default CampingListPage;
```

---

## 💡 Avantages de cette Architecture

### 1. **DRY (Don't Repeat Yourself)**
```javascript
// ❌ Sans useAPI (répétitif)
fetch('http://localhost:8080/api/camping.php', {
  credentials: 'include',
  headers: { 'Content-Type': 'application/json' }
})
// Répété 50 fois dans le code !

// ✅ Avec useAPI (une seule fois)
const { getAll } = useCamping();
getAll(); // Simple et clair !
```

---

### 2. **Maintenance Facile**
```javascript
// Si l'URL change :
// ❌ Sans useAPI : Modifier 50 fichiers
// ✅ Avec useAPI : Modifier 1 ligne dans useAPI.js

const API_BASE = 'http://localhost:8080'; // ← Changer ici seulement !
```

---

### 3. **Gestion d'Erreurs Centralisée**
```javascript
// Toutes les erreurs sont gérées au même endroit
const request = async (...) => {
  try {
    // ...
  } catch (err) {
    setError(err.message); // ← Centralisé !
    throw err;
  }
};
```

---

### 4. **Code Lisible**
```javascript
// ❌ Sans hook
fetch('http://localhost:8080/api/camping.php', { credentials: 'include' })
  .then(res => res.json())
  .then(data => setCampings(data))
  .catch(err => console.error(err));

// ✅ Avec hook
const { getAll } = useCamping();
getAll().then(setCampings);
```

---

## 🚀 Prochaines Étapes

Maintenant que vous comprenez `useAPI.js`, vous pouvez :

1. **Créer de nouveaux hooks** pour d'autres ressources
2. **Utiliser les hooks existants** dans vos composants
3. **Personnaliser les requêtes** selon vos besoins

---

**Date :** 3 octobre 2025  
**Fichier :** `frontend/src/hooks/useAPI.js`  
**Niveau :** Expliqué pour débutants  
**Temps de lecture :** 45 minutes
