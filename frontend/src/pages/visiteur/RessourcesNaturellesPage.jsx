import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';
import RessourcesMap from '../../components/Maps/RessourcesMap';

export default function RessourcesNaturellesPage() {
  const [ressources, setRessources] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('http://localhost:8080/api/public/ressources_naturelles.php')
      .then(res => res.json())
      .then(response => {
        // L'API retourne {success: true, data: [...]}
        const data = response.data || response;
        setRessources(Array.isArray(data) ? data : []);
        setLoading(false);
      })
      .catch(err => {
        console.error('Erreur:', err);
        setRessources([]);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return (
      <div className="page-content">
        <div className="card">
          <p style={{ textAlign: 'center', padding: '2rem' }}>Chargement des ressources...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="page-content">
      <h2 className="text-gradient">
        <FontAwesomeIcon icon={icons.leaf} /> Ressources Naturelles
      </h2>
      
      {/* Légende */}
      <div className="card" style={{ marginBottom: '2rem' }}>
        <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', marginBottom: '1rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#27ae60', borderRadius: '50%' }}></span>
            <span>Flore ({ressources.filter(r => r.type === 'Flore').length})</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#e67e22', borderRadius: '50%' }}></span>
            <span>Faune ({ressources.filter(r => r.type === 'Faune').length})</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#3498db', borderRadius: '50%' }}></span>
            <span>Marine ({ressources.filter(r => r.type === 'Marine').length})</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ width: '20px', height: '20px', backgroundColor: '#95a5a6', borderRadius: '50%' }}></span>
            <span>Géologie ({ressources.filter(r => r.type === 'Géologie').length})</span>
          </div>
        </div>
        <p style={{ fontSize: '0.9rem', opacity: 0.7 }}>
          Total: {ressources.length} ressources
        </p>
      </div>

      {/* Carte */}
      <div className="card" style={{ padding: 0, overflow: 'hidden', marginBottom: '2rem' }}>
        <RessourcesMap ressources={ressources} />
      </div>

      {/* Liste par type */}
      <div>
        <h3 className="text-gradient" style={{ marginBottom: '1.5rem' }}>
          <FontAwesomeIcon icon={icons.tree} /> Détail des Ressources
        </h3>
        
        {['Flore', 'Faune', 'Marine', 'Géologie'].map(type => {
          const items = ressources.filter(r => r.type === type);
          if (items.length === 0) return null;
          
          return (
            <div key={type} className="card" style={{ marginBottom: '1.5rem' }}>
              <h4 style={{ 
                marginBottom: '1rem',
                color: type === 'Flore' ? '#27ae60' :
                       type === 'Faune' ? '#e67e22' :
                       type === 'Marine' ? '#3498db' : '#95a5a6'
              }}>
                {type} ({items.length})
              </h4>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))', gap: '1rem' }}>
                {items.map(item => (
                  <div key={item.id} style={{
                    padding: '1rem',
                    border: '1px solid #e0e0e0',
                    borderRadius: '8px',
                    backgroundColor: '#f9f9f9'
                  }}>
                    <h5 style={{ margin: '0 0 0.5rem 0', fontSize: '1rem' }}>{item.nom}</h5>
                    <p style={{ margin: 0, fontSize: '0.85rem', opacity: 0.7 }}>
                      État: {item.etat}
                    </p>
                  </div>
                ))}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
