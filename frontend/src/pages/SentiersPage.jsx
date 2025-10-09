import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../utils/icons';
import SentiersMap from '../components/Maps/SentiersMap';

export default function SentiersPage() {
  const [sentiers, setSentiers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('http://localhost:8080/api/public/sentiers.php')
      .then(res => res.json())
      .then(response => {
        // L'API retourne {success: true, data: [...]}
        const data = response.data || response;
        setSentiers(Array.isArray(data) ? data : []);
        setLoading(false);
      })
      .catch(err => {
        console.error('Erreur:', err);
        setSentiers([]);
        setLoading(false);
      });
  }, []);

  const getDifficultyColor = (difficulte) => {
    switch(difficulte?.toLowerCase()) {
      case 'facile': return '#27ae60';
      case 'moyen': return '#f39c12';
      case 'difficile': return '#e74c3c';
      default: return '#95a5a6';
    }
  };

  if (loading) {
    return (
      <div className="page-content">
        <div className="card">
          <p style={{ textAlign: 'center', padding: '2rem' }}>Chargement des sentiers...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="page-content">
      <h2 className="text-gradient">
        <FontAwesomeIcon icon={icons.map} /> Carte des Sentiers
      </h2>
      
      {/* Légende */}
      <div className="card" style={{ marginBottom: '2rem' }}>
        <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#27ae60', borderRadius: '50%' }}></span>
            <span>Facile ({sentiers.filter(s => s.difficulte === 'facile').length})</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#f39c12', borderRadius: '50%' }}></span>
            <span>Moyen ({sentiers.filter(s => s.difficulte === 'moyen').length})</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#e74c3c', borderRadius: '50%' }}></span>
            <span>Difficile ({sentiers.filter(s => s.difficulte === 'difficile').length})</span>
          </div>
        </div>
      </div>

      {/* Carte */}
      <div className="card" style={{ padding: 0, overflow: 'hidden', marginBottom: '2rem' }}>
        <SentiersMap sentiers={sentiers} />
      </div>

      {/* Liste des sentiers */}
      <div>
        <h3 className="text-gradient" style={{ marginBottom: '1.5rem' }}>
          <FontAwesomeIcon icon={icons.hiking} /> Liste des Sentiers ({sentiers.length})
        </h3>
        <div style={{ display: 'grid', gap: '1rem' }}>
          {sentiers.map(sentier => (
            <div key={sentier.id} className="card" style={{
              borderLeft: `4px solid ${getDifficultyColor(sentier.difficulte)}`
            }}>
              <h4 style={{ margin: '0 0 0.5rem 0' }}>{sentier.nom}</h4>
              <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                <strong>Difficulté:</strong> 
                <span style={{ 
                  marginLeft: '0.5rem',
                  padding: '2px 8px',
                  borderRadius: '4px',
                  backgroundColor: getDifficultyColor(sentier.difficulte),
                  color: 'white',
                  fontSize: '0.85rem',
                  textTransform: 'capitalize'
                }}>
                  {sentier.difficulte}
                </span>
              </p>
              {sentier.description && (
                <p style={{ margin: '0.5rem 0 0 0', fontSize: '0.9rem', opacity: 0.7 }}>
                  {sentier.description}
                </p>
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
