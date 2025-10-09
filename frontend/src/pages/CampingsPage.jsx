import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../utils/icons';
import CampingsMap from '../components/Maps/CampingsMap';

export default function CampingsPage() {
  const [campings, setCampings] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('http://localhost:8080/api/camping.php')
      .then(res => res.json())
      .then(response => {
        // L'API retourne {success: true, data: [...]}
        const data = response.data || response;
        setCampings(Array.isArray(data) ? data : []);
        setLoading(false);
      })
      .catch(err => {
        console.error('Erreur:', err);
        setCampings([]);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return (
      <div className="page-content">
        <div className="card">
          <p style={{ textAlign: 'center', padding: '2rem' }}>Chargement des campings...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="page-content">
      <h2 className="text-gradient">
        <FontAwesomeIcon icon={icons.camping} /> Campings Disponibles
      </h2>
      
      <div className="card" style={{ marginBottom: '2rem' }}>
        <p style={{ fontSize: '1.1rem' }}>
          <strong>{campings.length}</strong> campings disponibles dans le Parc National des Calanques
        </p>
      </div>

      {/* Carte */}
      <div className="card" style={{ padding: 0, overflow: 'hidden', marginBottom: '2rem' }}>
        <CampingsMap campings={campings} />
      </div>

      {/* Liste des campings */}
      <div>
        <h3 className="text-gradient" style={{ marginBottom: '1.5rem' }}>
          <FontAwesomeIcon icon={icons.camping} /> Liste des Campings
        </h3>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))', gap: '1.5rem' }}>
          {campings.map(camping => (
            <div key={camping.id} className="card" style={{ borderTop: '4px solid #27ae60' }}>
              <h4 style={{ margin: '0 0 0.5rem 0', color: '#27ae60' }}>{camping.nom}</h4>
              <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                <FontAwesomeIcon icon={icons.mapMarked} /> {camping.localisation}
              </p>
              <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                <strong>Capacité:</strong> {camping.capacite} places
              </p>
              <button style={{
                marginTop: '1rem',
                padding: '0.75rem 1.5rem',
                backgroundColor: '#27ae60',
                color: 'white',
                border: 'none',
                borderRadius: '8px',
                cursor: 'pointer',
                width: '100%',
                fontWeight: 'bold'
              }}>
                <FontAwesomeIcon icon={icons.calendar} /> Réserver
              </button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
