# 🗺️ Guide d'Installation Leaflet - Cartes Interactives

## 📦 Installation

```bash
cd frontend
npm install leaflet react-leaflet
```

## 🎨 Composant Carte Sentiers Complet

Créer `frontend/src/components/Maps/SentiersMap.jsx`:

```jsx
import { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import { useSentier } from '../../hooks/useAPI';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix des icônes Leaflet avec Vite
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconUrl,
  iconRetinaUrl,
  shadowUrl
});

// Icônes personnalisées par difficulté
const markerIcons = {
  'Facile': L.icon({
    iconUrl: 'data:image/svg+xml;base64,' + btoa(`
      <svg width="25" height="41" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.5 0C5.6 0 0 5.6 0 12.5c0 9.4 12.5 28.5 12.5 28.5S25 21.9 25 12.5C25 5.6 19.4 0 12.5 0z" fill="#27ae60"/>
        <circle cx="12.5" cy="12.5" r="6" fill="white"/>
      </svg>
    `),
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  }),
  'Moyen': L.icon({
    iconUrl: 'data:image/svg+xml;base64,' + btoa(`
      <svg width="25" height="41" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.5 0C5.6 0 0 5.6 0 12.5c0 9.4 12.5 28.5 12.5 28.5S25 21.9 25 12.5C25 5.6 19.4 0 12.5 0z" fill="#f39c12"/>
        <circle cx="12.5" cy="12.5" r="6" fill="white"/>
      </svg>
    `),
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  }),
  'Difficile': L.icon({
    iconUrl: 'data:image/svg+xml;base64,' + btoa(`
      <svg width="25" height="41" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.5 0C5.6 0 0 5.6 0 12.5c0 9.4 12.5 28.5 12.5 28.5S25 21.9 25 12.5C25 5.6 19.4 0 12.5 0z" fill="#e74c3c"/>
        <circle cx="12.5" cy="12.5" r="6" fill="white"/>
      </svg>
    `),
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  })
};

export default function SentiersMap() {
  const [sentiers, setSentiers] = useState([]);
  const [loading, setLoading] = useState(true);
  const { getAll } = useSentier();

  useEffect(() => {
    loadSentiers();
  }, []);

  const loadSentiers = async () => {
    try {
      const data = await getAll();
      setSentiers(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Erreur:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div style={{ padding: '2rem', textAlign: 'center' }}>Chargement carte...</div>;
  }

  // Centre: Parc National des Calanques
  const center = [43.2094, 5.4371];

  return (
    <MapContainer
      center={center}
      zoom={12}
      style={{ height: '600px', width: '100%', borderRadius: '16px' }}
      scrollWheelZoom={true}
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />

      {sentiers.map((sentier) => {
        // Coordonnées exemple (à remplacer par vraies coordonnées)
        const position = [
          43.2094 + (Math.random() - 0.5) * 0.1,
          5.4371 + (Math.random() - 0.5) * 0.1
        ];

        return (
          <Marker
            key={sentier.id}
            position={position}
            icon={markerIcons[sentier.difficulte] || markerIcons['Moyen']}
          >
            <Popup>
              <div style={{ minWidth: '200px' }}>
                <h3 style={{ marginBottom: '0.5rem', color: '#2c3e50' }}>
                  {sentier.nom}
                </h3>
                <p style={{ margin: '0.25rem 0' }}>
                  <strong>Difficulté:</strong> {sentier.difficulte}
                </p>
                <p style={{ margin: '0.25rem 0' }}>
                  <strong>Longueur:</strong> {sentier.longueur} km
                </p>
                {sentier.duree_estimee && (
                  <p style={{ margin: '0.25rem 0' }}>
                    <strong>Durée:</strong> {sentier.duree_estimee}
                  </p>
                )}
                <button
                  style={{
                    marginTop: '0.75rem',
                    padding: '0.5rem 1rem',
                    background: '#3498db',
                    color: 'white',
                    border: 'none',
                    borderRadius: '8px',
                    cursor: 'pointer',
                    width: '100%'
                  }}
                  onClick={() => alert(`Détails: ${sentier.nom}`)}
                >
                  Voir détails
                </button>
              </div>
            </Popup>
          </Marker>
        );
      })}
    </MapContainer>
  );
}
```

## 🏕️ Composant Carte Campings

Créer `frontend/src/components/Maps/CampingMap.jsx`:

```jsx
import { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import { useCamping } from '../../hooks/useAPI';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const campingIcon = L.icon({
  iconUrl: 'data:image/svg+xml;base64,' + btoa(`
    <svg width="25" height="41" xmlns="http://www.w3.org/2000/svg">
      <path d="M12.5 0C5.6 0 0 5.6 0 12.5c0 9.4 12.5 28.5 12.5 28.5S25 21.9 25 12.5C25 5.6 19.4 0 12.5 0z" fill="#27ae60"/>
      <text x="12.5" y="15" font-size="14" text-anchor="middle" fill="white">⛺</text>
    </svg>
  `),
  iconSize: [25, 41],
  iconAnchor: [12, 41]
});

export default function CampingMap() {
  const [campings, setCampings] = useState([]);
  const { getAll } = useCamping();

  useEffect(() => {
    getAll().then(data => setCampings(Array.isArray(data) ? data : []));
  }, []);

  return (
    <MapContainer
      center={[43.2094, 5.4371]}
      zoom={12}
      style={{ height: '600px', width: '100%', borderRadius: '16px' }}
    >
      <TileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />

      {campings.map((camping) => {
        const position = [
          43.2094 + (Math.random() - 0.5) * 0.1,
          5.4371 + (Math.random() - 0.5) * 0.1
        ];

        return (
          <Marker key={camping.id} position={position} icon={campingIcon}>
            <Popup>
              <div style={{ minWidth: '200px' }}>
                <h3>{camping.nom}</h3>
                <p><strong>Localisation:</strong> {camping.localisation}</p>
                <p><strong>Capacité:</strong> {camping.capacite} places</p>
                {camping.equipements && (
                  <p><strong>Équipements:</strong> {camping.equipements}</p>
                )}
                <button
                  style={{
                    marginTop: '1rem',
                    padding: '0.5rem 1rem',
                    background: '#27ae60',
                    color: 'white',
                    border: 'none',
                    borderRadius: '8px',
                    width: '100%'
                  }}
                >
                  Réserver
                </button>
              </div>
            </Popup>
          </Marker>
        );
      })}
    </MapContainer>
  );
}
```

## 🌿 Composant Carte Ressources

Créer `frontend/src/components/Maps/RessourcesMap.jsx`:

```jsx
import { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Circle, Popup } from 'react-leaflet';
import { useRessources } from '../../hooks/useAPI';
import 'leaflet/dist/leaflet.css';

const typeColors = {
  'Forêt': '#27ae60',
  'Marine': '#3498db',
  'Garrigue': '#f39c12',
  'Rocheux': '#95a5a6'
};

export default function RessourcesMap() {
  const [ressources, setRessources] = useState([]);
  const { getAll } = useRessources();

  useEffect(() => {
    getAll().then(data => setRessources(Array.isArray(data) ? data : []));
  }, []);

  return (
    <MapContainer
      center={[43.2094, 5.4371]}
      zoom={12}
      style={{ height: '600px', width: '100%', borderRadius: '16px' }}
    >
      <TileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />

      {ressources.map((ressource) => {
        const position = [
          43.2094 + (Math.random() - 0.5) * 0.15,
          5.4371 + (Math.random() - 0.5) * 0.15
        ];

        return (
          <Circle
            key={ressource.id}
            center={position}
            radius={500}
            pathOptions={{
              color: typeColors[ressource.type] || '#95a5a6',
              fillColor: typeColors[ressource.type] || '#95a5a6',
              fillOpacity: 0.3
            }}
          >
            <Popup>
              <div>
                <h3>{ressource.nom}</h3>
                <p><strong>Type:</strong> {ressource.type}</p>
                <p>{ressource.description}</p>
              </div>
            </Popup>
          </Circle>
        );
      })}
    </MapContainer>
  );
}
```

## 📝 Utilisation dans DashboardPage.jsx

```jsx
import SentiersMap from '../components/Maps/SentiersMap';
import CampingMap from '../components/Maps/CampingMap';
import RessourcesMap from '../components/Maps/RessourcesMap';

function SentiersPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🗺️ Carte des Sentiers</h2>
      <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
        <SentiersMap />
      </div>
    </div>
  );
}

function CampingPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🏕️ Campings Disponibles</h2>
      <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
        <CampingMap />
      </div>
    </div>
  );
}

function RessourcesPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🌿 Ressources Naturelles</h2>
      <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
        <RessourcesMap />
      </div>
    </div>
  );
}
```

## 🗄️ Migration Base de Données

Pour ajouter les coordonnées GPS:

```sql
-- Ajouter colonnes
ALTER TABLE sentier 
ADD COLUMN latitude DECIMAL(10, 8) AFTER longueur,
ADD COLUMN longitude DECIMAL(11, 8) AFTER latitude;

ALTER TABLE camping 
ADD COLUMN latitude DECIMAL(10, 8) AFTER capacite,
ADD COLUMN longitude DECIMAL(11, 8) AFTER latitude;

ALTER TABLE ressource_naturelle 
ADD COLUMN latitude DECIMAL(10, 8) AFTER description,
ADD COLUMN longitude DECIMAL(11, 8) AFTER latitude;

-- Exemple données Calanques
UPDATE sentier SET latitude = 43.2094, longitude = 5.4371 WHERE id = 1;
UPDATE camping SET latitude = 43.2150, longitude = 5.4500 WHERE id = 1;
```

## 🎨 Style CSS pour Cartes

Ajouter dans `animations.css`:

```css
/* Leaflet Custom Styling */
.leaflet-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.leaflet-popup-content-wrapper {
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.leaflet-popup-content h3 {
  margin: 0 0 0.5rem;
  color: var(--parc-bleu);
  font-size: 1.2rem;
}

.leaflet-popup-content p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
}
```

## 🧪 Test Local

```bash
# Terminal 1: Backend
cd backend
php -S localhost:8080

# Terminal 2: Frontend
cd frontend
npm run dev
```

Accès: `http://localhost:5173/dashboard/sentiers`

## ✅ Checklist

- [x] Installation `npm install leaflet react-leaflet`
- [ ] Créer dossier `frontend/src/components/Maps/`
- [ ] Créer `SentiersMap.jsx`
- [ ] Créer `CampingMap.jsx`
- [ ] Créer `RessourcesMap.jsx`
- [ ] Ajouter colonnes GPS en base
- [ ] Remplir coordonnées GPS
- [ ] Tester affichage cartes
- [ ] Personnaliser icônes
- [ ] Ajouter filtres/recherche

---

**🎉 Cartes interactives prêtes !**
