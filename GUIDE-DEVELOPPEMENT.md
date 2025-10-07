# 🚀 Guide de Développement Post-Refactorisation

## 📋 Checklist pour Ajouter une Nouvelle Fonctionnalité

### Backend (PHP)

#### 1. Créer le Modèle (extends Database)

```php
<?php
// backend/models/NouveauModele.php
require_once __DIR__ . '/Database.php';

class NouveauModele extends Database {
    private $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM table_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM table_name WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(string $param1, string $param2): ?array {
        $stmt = $this->db->prepare(
            "INSERT INTO table_name (col1, col2) VALUES (?, ?)"
        );
        $stmt->execute([$param1, $param2]);
        return $this->findById($this->db->lastInsertId());
    }

    public function update(int $id, ?string $param1, ?string $param2): ?array {
        $current = $this->findById($id);
        if (!$current) return null;
        
        $stmt = $this->db->prepare(
            "UPDATE table_name SET col1 = ?, col2 = ? WHERE id = ?"
        );
        $stmt->execute([
            $param1 ?? $current['col1'],
            $param2 ?? $current['col2'],
            $id
        ]);
        
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM table_name WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
```

**✅ Checklist Modèle:**
- [ ] Extend Database
- [ ] Constructor appelle $this->connect()
- [ ] Typage strict (int, string, ?array, etc.)
- [ ] Return types explicites
- [ ] findAll() retourne array
- [ ] findById() retourne ?array
- [ ] create() retourne ?array (ou null si échec)
- [ ] update() retourne ?array
- [ ] delete() retourne bool

---

#### 2. Créer le Contrôleur API (extends BaseController)

```php
<?php
// backend/api/nouveau-endpoint.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/NouveauModele.php';
require_once __DIR__ . '/../utils/AuthMiddelware.php';
require_once __DIR__ . '/../utils/BaseController.php';

class NouveauController extends BaseController {
    private NouveauModele $model;
    
    public function __construct() {
        $this->setCorsHeaders();  // CORS automatique
        $this->model = new NouveauModele();
    }
    
    public function handle(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                $this->jsonError("Méthode non autorisée", 405);
        }
    }
    
    private function handleGet(): void {
        // GET = Public (ou pas, selon besoin)
        if (isset($_GET['id'])) {
            $item = $this->model->findById($_GET['id']);
            $item ? $this->jsonSuccess($item) : $this->jsonError("Non trouvé", 404);
        } else {
            $this->jsonSuccess($this->model->findAll());
        }
    }
    
    private function handlePost(): void {
        // POST = Admin uniquement (ou pas)
        AuthMiddleware::requireAdmin();
        
        $data = $this->getJsonInput();
        
        if (empty($data['param1']) || empty($data['param2'])) {
            $this->jsonError("Données manquantes");
        }
        
        $result = $this->model->create($data['param1'], $data['param2']);
        $result ? $this->jsonSuccess($result, 201) : $this->jsonError("Erreur création");
    }
    
    private function handlePut(): void {
        AuthMiddleware::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['id'])) {
            $this->jsonError("ID manquant");
        }
        
        $result = $this->model->update(
            $data['id'],
            $data['param1'] ?? null,
            $data['param2'] ?? null
        );
        
        $result ? $this->jsonSuccess($result) : $this->jsonError("Erreur mise à jour");
    }
    
    private function handleDelete(): void {
        AuthMiddleware::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['id'])) {
            $this->jsonError("ID manquant");
        }
        
        $result = $this->model->delete($data['id']);
        $result ? $this->jsonSuccess(['deleted' => true]) : $this->jsonError("Erreur suppression");
    }
}

// IMPORTANT : Exécution
$controller = new NouveauController();
$controller->handle();
```

**✅ Checklist Contrôleur:**
- [ ] Extend BaseController
- [ ] setCorsHeaders() dans __construct()
- [ ] Méthode handle() pour router les requêtes
- [ ] Utiliser jsonSuccess() pour succès
- [ ] Utiliser jsonError() pour erreurs
- [ ] Utiliser getJsonInput() pour body JSON
- [ ] AuthMiddleware::requireAdmin() si besoin
- [ ] Instancier et appeler handle() à la fin du fichier

---

### Frontend (React)

#### 3. Créer le Hook Custom

```javascript
// frontend/src/hooks/useNouveauModele.js
import { useCallback } from 'react';
import { useAPI } from './useAPI';

export function useNouveauModele() {
  const { get, post, put, del } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/nouveau-endpoint.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/nouveau-endpoint.php?id=${id}`);
    return result.data || result;
  }, [get]);

  const create = useCallback(async (item) => {
    const result = await post('/api/nouveau-endpoint.php', item);
    return result.data || result;
  }, [post]);

  const update = useCallback(async (item) => {
    const result = await put('/api/nouveau-endpoint.php', item);
    return result.data || result;
  }, [put]);

  const remove = useCallback(async (id) => {
    const result = await del('/api/nouveau-endpoint.php', { id });
    return result.data || result;
  }, [del]);

  return { getAll, getById, create, update, remove };
}
```

**✅ Checklist Hook:**
- [ ] Import useAPI
- [ ] Utiliser useCallback pour chaque méthode
- [ ] getAll, getById, create, update, remove
- [ ] Retourner result.data || result (compatibilité)
- [ ] Export du hook

---

#### 4. Utiliser le Hook dans un Composant

```javascript
// frontend/src/pages/NouvellePage.jsx
import { useState, useEffect } from 'react';
import { useNouveauModele } from '../hooks/useNouveauModele';
import '../css/animations.css';

export default function NouvellePage() {
  const { getAll, create, update, remove } = useNouveauModele();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadItems();
  }, []);

  const loadItems = async () => {
    setLoading(true);
    try {
      const data = await getAll();
      setItems(data);
    } catch (error) {
      console.error('Erreur chargement:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="container-fluid">Chargement...</div>;
  }

  return (
    <div className="container-fluid" style={{ paddingTop: '2rem' }}>
      <h2 className="text-gradient">Titre de la Page</h2>
      
      <div className="grid grid-3">
        {items.map((item) => (
          <div key={item.id} className="card">
            <h3>{item.nom}</h3>
            <p>{item.description}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
```

**✅ Checklist Composant:**
- [ ] Import du hook personnalisé
- [ ] Import animations.css
- [ ] useState pour données
- [ ] useEffect pour chargement initial
- [ ] Gestion du loading
- [ ] Utiliser classes CSS (card, grid, text-gradient, etc.)
- [ ] Animations au besoin

---

## 🎨 Classes CSS Disponibles

### Layout
```css
.container-fluid      → Max-width 1400px, padding auto
.section              → Padding 4rem, overflow hidden
```

### Grilles
```css
.grid                 → Display grid, gap 2rem
.grid-2               → 2 colonnes responsive
.grid-3               → 3 colonnes responsive
.grid-4               → 4 colonnes responsive
```

### Composants
```css
.card                 → Carte blanche, border-radius 16px, shadow
.btn                  → Bouton avec effet ripple
.btn-primary          → Bouton bleu dégradé
```

### Animations
```css
.fade-in              → Apparition progressive
.slide-in-left        → Glissement depuis gauche
.slide-in-right       → Glissement depuis droite
.text-gradient        → Texte avec dégradé
.img-fluid            → Image responsive avec hover
```

### Header
```css
.header-fixed         → Header fixe en haut
.header-fixed.scrolled → Header après scroll
.header-fixed.transparent → Header transparent
.scroll-indicator     → Barre progression scroll
```

---

## 🎯 Animations au Scroll

### Utiliser IntersectionObserver

```javascript
import { useState, useEffect } from 'react';

export default function Page() {
  const [visibleSections, setVisibleSections] = useState([]);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            setVisibleSections((prev) => [...prev, entry.target.id]);
          }
        });
      },
      { threshold: 0.1 }
    );

    document.querySelectorAll('[data-animate]').forEach((el) => {
      observer.observe(el);
    });

    return () => observer.disconnect();
  }, []);

  return (
    <div>
      <section
        id="section1"
        data-animate
        className={visibleSections.includes('section1') ? 'fade-in' : ''}
      >
        Contenu qui s'anime
      </section>
    </div>
  );
}
```

**✅ Checklist Animation:**
- [ ] useState pour sections visibles
- [ ] useEffect avec IntersectionObserver
- [ ] Attribut data-animate sur sections
- [ ] id unique sur chaque section
- [ ] className conditionnel avec animation

---

## 📝 Conventions de Code

### PHP
- ✅ Typage strict : `function create(string $nom, int $age): ?array`
- ✅ Return types explicites
- ✅ CamelCase pour méthodes : `findById()`, `createUser()`
- ✅ PascalCase pour classes : `UserController`, `AuthModel`
- ✅ Variables avec $ : `$db`, `$result`
- ✅ Constantes MAJUSCULES : `API_BASE`, `DB_HOST`

### JavaScript/React
- ✅ CamelCase pour fonctions : `loadItems()`, `handleClick()`
- ✅ PascalCase pour composants : `HomePage`, `AnimatedHeader`
- ✅ Hooks commencent par "use" : `useAPI`, `useCamping`
- ✅ Destructuring : `const { get, post } = useAPI();`
- ✅ Arrow functions : `const handleClick = () => {}`
- ✅ Async/await : `await getAll()`

---

## 🧪 Tests Unitaires

### Backend (PHPUnit)

```php
<?php
// backend/tests/Unit/NouveauModeleTest.php
use PHPUnit\Framework\TestCase;

class NouveauModeleTest extends TestCase {
    private $model;
    
    protected function setUp(): void {
        $this->model = new NouveauModele();
    }
    
    public function testFindAll() {
        $result = $this->model->findAll();
        $this->assertIsArray($result);
    }
    
    public function testCreate() {
        $result = $this->model->create('Test', 'Description');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }
    
    protected function tearDown(): void {
        // Cleanup si nécessaire
    }
}
```

**Lancer les tests:**
```bash
cd backend
./vendor/bin/phpunit
```

---

## 🔒 Sécurité

### Validation Backend

```php
private function handlePost(): void {
    $data = $this->getJsonInput();
    
    // Validation
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $this->jsonError("Email invalide");
    }
    
    if (empty($data['nom']) || strlen($data['nom']) < 3) {
        $this->jsonError("Nom trop court");
    }
    
    // Sanitization
    $nom = htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8');
    
    // Traitement...
}
```

### Validation Frontend

```javascript
const handleSubmit = async (e) => {
  e.preventDefault();
  
  // Validation
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    setError('Email invalide');
    return;
  }
  
  if (!nom || nom.length < 3) {
    setError('Nom trop court');
    return;
  }
  
  // Envoi...
};
```

---

## 🚀 Déploiement

### Production Checklist
- [ ] Variables d'environnement (.env)
- [ ] CORS limité aux domaines autorisés
- [ ] HTTPS uniquement
- [ ] Tokens JWT avec expiration courte
- [ ] Rate limiting sur API
- [ ] Logs d'erreurs
- [ ] Minification CSS/JS
- [ ] Compression Gzip
- [ ] Cache headers
- [ ] CDN pour assets statiques

---

## 📚 Ressources

### Documentation
- PHP SOLID : https://phptherightway.com/
- React Hooks : https://react.dev/reference/react
- CSS Animations : https://developer.mozilla.org/en-US/docs/Web/CSS/animation

### Outils
- PHPStan : Analyse statique PHP
- ESLint : Linting JavaScript
- Prettier : Formatage code
- Lighthouse : Performance audit

---

**🎉 Bon développement !**

En suivant ces patterns, le code restera propre, maintenable et performant.
