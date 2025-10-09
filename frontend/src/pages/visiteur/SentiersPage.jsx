import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';
import SentiersMap from '../../components/Maps/SentiersMap';
import '../../css/SentiersPage.css';

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

  const getDifficultyClass = (difficulte) => {
    const diff = difficulte?.toLowerCase();
    if (diff === 'facile' || diff === 'moyen' || diff === 'difficile') {
      return diff;
    }
    return 'default';
  };

  if (loading) {
    return (
      <div className="page-content">
        <div className="card">
          <p className="sentiers-loading">Chargement des sentiers...</p>
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
      <div className="card sentiers-legend">
        <div className="sentiers-legend-items">
          <div className="sentiers-legend-item">
            <span className="sentiers-legend-dot facile"></span>
            <span>Facile ({sentiers.filter(s => s.difficulte === 'facile').length})</span>
          </div>
          <div className="sentiers-legend-item">
            <span className="sentiers-legend-dot moyen"></span>
            <span>Moyen ({sentiers.filter(s => s.difficulte === 'moyen').length})</span>
          </div>
          <div className="sentiers-legend-item">
            <span className="sentiers-legend-dot difficile"></span>
            <span>Difficile ({sentiers.filter(s => s.difficulte === 'difficile').length})</span>
          </div>
        </div>
      </div>

      {/* Carte */}
      <div className="card sentiers-map-card">
        <SentiersMap sentiers={sentiers} />
      </div>

      {/* Liste des sentiers */}
      <div>
        <h3 className="text-gradient sentiers-list-title">
          <FontAwesomeIcon icon={icons.hiking} /> Liste des Sentiers ({sentiers.length})
        </h3>
        <div className="sentiers-grid">
          {sentiers.map(sentier => {
            const difficultyClass = getDifficultyClass(sentier.difficulte);
            return (
              <div key={sentier.id} className={`card sentier-card border-${difficultyClass}`}>
                <h4 className="sentier-card-title">{sentier.nom}</h4>
                <p className="sentier-card-info">
                  <strong>Difficulté:</strong> 
                  <span className={`sentier-difficulty-badge ${difficultyClass}`}>
                    {sentier.difficulte}
                  </span>
                </p>
                {sentier.description && (
                  <p className="sentier-card-description">
                    {sentier.description}
                  </p>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
