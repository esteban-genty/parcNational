import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';
import { useCamping } from '../../hooks/useAPI';
import '../../css/dashboard-new.css';

/**
 * Page d'administration des campings
 * Permet à l'admin de voir, modifier et supprimer les campings
 */
export default function CampingsAdminPage() {
  // État local pour stocker la liste des campings
  const [campings, setCampings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Hook personnalisé pour communiquer avec l'API
  const { getAll, remove } = useCamping();

  // Charger les campings au démarrage
  useEffect(() => {
    chargerCampings();
  }, []);

  /**
   * Fonction pour récupérer tous les campings depuis l'API
   */
  const chargerCampings = async () => {
    try {
      setLoading(true);
      const data = await getAll();
      setCampings(data || []);
      setError(null);
    } catch (err) {
      console.error('Erreur chargement campings:', err);
      setError('Impossible de charger les campings');
    } finally {
      setLoading(false);
    }
  };

  /**
   * Fonction pour supprimer un camping
   * Demande confirmation avant suppression
   */
  const supprimerCamping = async (id, nom) => {
    // Demander confirmation
    if (!window.confirm(`Voulez-vous vraiment supprimer "${nom}" ?`)) {
      return;
    }

    try {
      await remove(id);
      alert('Camping supprimé avec succès');
      // Recharger la liste
      chargerCampings();
    } catch (err) {
      console.error('Erreur suppression:', err);
      alert('Erreur lors de la suppression');
    }
  };

  // Affichage pendant le chargement
  if (loading) {
    return (
      <div className="page-content fade-in">
        <p style={{ textAlign: 'center', fontSize: '1.2rem' }}>
          <FontAwesomeIcon icon={icons.spinner} spin /> Chargement...
        </p>
      </div>
    );
  }

  // Affichage en cas d'erreur
  if (error) {
    return (
      <div className="page-content fade-in">
        <div className="card" style={{ background: '#e74c3c', color: 'white' }}>
          <h3><FontAwesomeIcon icon={icons.warning} /> Erreur</h3>
          <p>{error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="page-content fade-in">
      {/* En-tête de la page */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <div>
          <h2 className="text-gradient">
            <FontAwesomeIcon icon={icons.camping} /> Gestion des Campings
          </h2>
          <p style={{ opacity: 0.8 }}>
            {campings.length} camping{campings.length > 1 ? 's' : ''} au total
          </p>
        </div>

        {/* Bouton pour ajouter un nouveau camping */}
        <Link to="/camping/create" className="btn btn-success">
          <FontAwesomeIcon icon={icons.plus} /> Ajouter un camping
        </Link>
      </div>

      {/* Tableau des campings */}
      <div className="card">
        {campings.length === 0 ? (
          <p style={{ textAlign: 'center', padding: '2rem', opacity: 0.7 }}>
            Aucun camping trouvé
          </p>
        ) : (
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ borderBottom: '2px solid #ddd' }}>
                <th style={{ padding: '1rem', textAlign: 'left' }}>ID</th>
                <th style={{ padding: '1rem', textAlign: 'left' }}>Nom</th>
                <th style={{ padding: '1rem', textAlign: 'left' }}>Localisation</th>
                <th style={{ padding: '1rem', textAlign: 'center' }}>Capacité</th>
                <th style={{ padding: '1rem', textAlign: 'center' }}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {campings.map((camping) => (
                <tr key={camping.id} style={{ borderBottom: '1px solid #eee' }}>
                  {/* ID du camping */}
                  <td style={{ padding: '1rem' }}>#{camping.id}</td>

                  {/* Nom du camping */}
                  <td style={{ padding: '1rem', fontWeight: 'bold' }}>
                    {camping.nom}
                  </td>

                  {/* Localisation */}
                  <td style={{ padding: '1rem', opacity: 0.8 }}>
                    {camping.localisation || 'Non définie'}
                  </td>

                  {/* Capacité */}
                  <td style={{ padding: '1rem', textAlign: 'center' }}>
                    <span style={{ 
                      background: '#3498db', 
                      color: 'white', 
                      padding: '0.3rem 0.8rem', 
                      borderRadius: '20px',
                      fontSize: '0.9rem'
                    }}>
                      {camping.capacite || 0} places
                    </span>
                  </td>

                  {/* Boutons d'actions */}
                  <td style={{ padding: '1rem', textAlign: 'center' }}>
                    <div style={{ display: 'flex', gap: '0.5rem', justifyContent: 'center' }}>
                      {/* Bouton Voir */}
                      <Link 
                        to={`/camping/${camping.id}`} 
                        className="btn btn-primary"
                        style={{ padding: '0.5rem 1rem', fontSize: '0.9rem' }}
                        title="Voir les détails"
                      >
                        <FontAwesomeIcon icon={icons.eye} /> Voir
                      </Link>

                      {/* Bouton Modifier */}
                      <Link 
                        to={`/camping/edit/${camping.id}`} 
                        className="btn btn-warning"
                        style={{ padding: '0.5rem 1rem', fontSize: '0.9rem' }}
                        title="Modifier le camping"
                      >
                        <FontAwesomeIcon icon={icons.edit} /> Modifier
                      </Link>

                      {/* Bouton Supprimer */}
                      <button
                        onClick={() => supprimerCamping(camping.id, camping.nom)}
                        className="btn btn-danger"
                        style={{ padding: '0.5rem 1rem', fontSize: '0.9rem' }}
                        title="Supprimer le camping"
                      >
                        <FontAwesomeIcon icon={icons.trash} /> Supprimer
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
}
