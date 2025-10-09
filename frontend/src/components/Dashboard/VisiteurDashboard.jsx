import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';
import { useNotifications } from '../../hooks/useAPI';
import MeteoWidget from './MeteoWidget';
import '../../css/animations.css';
import bgDashboard from '../../assets/bg-dashboard.jpg';

export default function VisiteurDashboard({ user }) {
  const [notifications, setNotifications] = useState([]);
  const { getActive } = useNotifications();

  useEffect(() => {
    loadNotifs();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const loadNotifs = async () => {
    try {
      const data = await getActive(5);
      setNotifications(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Erreur:', error);
    }
  };

  return (
    <div className="page-content fade-in">
      {/* Hero Header */}
      <div style={{
        height: '300px',
        background: `linear-gradient(135deg, rgba(30,77,123,0.85), rgba(46,169,200,0.75)), url(${bgDashboard}) center/cover`,
        borderRadius: '20px',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        color: 'white',
        textAlign: 'center',
        marginBottom: '3rem',
        boxShadow: '0 8px 32px rgba(0,0,0,0.2)'
      }}>
        <div>
          <h2 style={{ fontSize: '2.5rem', marginBottom: '1rem' }}>
            Bienvenue {user.nom} ! 👋
          </h2>
          <p style={{ fontSize: '1.2rem', opacity: 0.9 }}>
            Préservons ensemble ce patrimoine naturel exceptionnel
          </p>
        </div>
      </div>

      {/* Météo Widget */}
      <div style={{ marginBottom: '3rem' }}>
        <MeteoWidget />
      </div>

      {/* Quick Actions */}
      <div className="grid grid-3" style={{ marginBottom: '3rem' }}>
        <a href="/dashboard/sentiers" className="card" style={{
          textDecoration: 'none',
          background: 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)',
          color: 'white',
          textAlign: 'center',
          padding: '2rem'
        }}>
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>
            <FontAwesomeIcon icon={icons.map} />
          </div>
          <h3>Voir les Sentiers</h3>
          <p style={{ opacity: 0.9 }}>Explorez les parcours</p>
        </a>

        <a href="/dashboard/camping" className="card" style={{
          textDecoration: 'none',
          background: 'linear-gradient(135deg, #27ae60 0%, #229954 100%)',
          color: 'white',
          textAlign: 'center',
          padding: '2rem'
        }}>
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>
            <FontAwesomeIcon icon={icons.camping} />
          </div>
          <h3>Réserver un Camping</h3>
          <p style={{ opacity: 0.9 }}>Trouvez votre emplacement</p>
        </a>

        <a href="/dashboard/ressources" className="card" style={{
          textDecoration: 'none',
          background: 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)',
          color: 'white',
          textAlign: 'center',
          padding: '2rem'
        }}>
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>
            <FontAwesomeIcon icon={icons.leaf} />
          </div>
          <h3>Ressources Naturelles</h3>
          <p style={{ opacity: 0.9 }}>Découvrez la biodiversité</p>
        </a>
      </div>

      {/* Notifications Importantes */}
      {notifications.length > 0 && (
        <div>
          <h3 className="text-gradient" style={{ marginBottom: '1.5rem' }}>
            <FontAwesomeIcon icon={icons.bell} /> Alertes et Informations
          </h3>
          <div className="grid" style={{ gap: '1rem' }}>
            {notifications.slice(0, 3).map((notif) => (
              <div key={notif.id} className="card" style={{
                borderLeft: '4px solid #e74c3c'
              }}>
                <h4 style={{ color: '#e74c3c', marginBottom: '0.5rem' }}>
                  {notif.titre}
                </h4>
                <p>{notif.message}</p>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
