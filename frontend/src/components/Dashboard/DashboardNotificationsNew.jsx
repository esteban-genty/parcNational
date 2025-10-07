import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';
import { useNotifications } from '../../hooks/useAPI';
import '../../css/animations.css';

export default function DashboardNotifications({ isAdmin = false }) {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { getActive, generate, remove } = useNotifications();

  useEffect(() => {
    loadNotifications();
  }, []);

  const loadNotifications = async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await getActive(10);
      setNotifications(Array.isArray(data) ? data : []);
    } catch (err) {
      setError(err.message);
      console.error('Erreur chargement notifications:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleGenerate = async () => {
    try {
      const result = await generate();
      alert(`✅ ${result.message}\n\nGénérées: ${result.total} notifications`);
      loadNotifications(); // Recharger
    } catch (err) {
      alert('❌ Erreur génération: ' + err.message);
    }
  };

  const handleDelete = async (id) => {
    if (!confirm('Supprimer cette notification ?')) return;
    
    try {
      await remove(id);
      setNotifications(notifications.filter(n => n.id !== id));
    } catch (err) {
      alert('❌ Erreur suppression: ' + err.message);
    }
  };

  if (loading) {
    return (
      <div className="card">
        <p style={{ textAlign: 'center', padding: '2rem' }}>Chargement des notifications...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="card" style={{ border: '2px solid #e74c3c' }}>
        <p style={{ color: '#e74c3c' }}>❌ Erreur: {error}</p>
        <button onClick={loadNotifications} className="btn btn-primary" style={{ marginTop: '1rem' }}>
          Réessayer
        </button>
      </div>
    );
  }

  return (
    <div className="notifications-container">
      <div className="notifications-header" style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: '1.5rem'
      }}>
        <h3 className="text-gradient">
          <FontAwesomeIcon icon={icons.bell} /> Notifications Actives
        </h3>
        {isAdmin && (
          <button 
            onClick={handleGenerate} 
            className="btn btn-primary"
            style={{ fontSize: '0.9rem', padding: '0.5rem 1rem' }}
          >
            <FontAwesomeIcon icon={icons.plus} /> Générer
          </button>
        )}
      </div>

      {notifications.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '3rem' }}>
          <p style={{ fontSize: '3rem', marginBottom: '1rem' }}>✨</p>
          <p>Aucune notification active</p>
          {isAdmin && (
            <button onClick={handleGenerate} className="btn" style={{ marginTop: '1rem' }}>
              Générer des notifications
            </button>
          )}
        </div>
      ) : (
        <div className="grid" style={{ gap: '1rem' }}>
          {notifications.map((notif, index) => (
            <div 
              key={notif.id} 
              className="card notification-card"
              style={{
                animationDelay: `${index * 0.1}s`,
                borderLeft: `4px solid ${getNotificationColor(notif.type)}`
              }}
            >
              <div style={{
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'flex-start'
              }}>
                <div style={{ flex: 1 }}>
                  <h4 style={{
                    fontSize: '1.1rem',
                    marginBottom: '0.5rem',
                    color: getNotificationColor(notif.type)
                  }}>
                    {notif.titre}
                  </h4>
                  <p style={{ marginBottom: '0.75rem', lineHeight: '1.5' }}>
                    {notif.message}
                  </p>
                  <p style={{
                    fontSize: '0.85rem',
                    opacity: 0.7
                  }}>
                    <FontAwesomeIcon icon={icons.calendar} /> {new Date(notif.date_envoi).toLocaleDateString('fr-FR', {
                      day: 'numeric',
                      month: 'long',
                      year: 'numeric'
                    })}
                  </p>
                </div>
                
                {isAdmin && (
                  <button
                    onClick={() => handleDelete(notif.id)}
                    style={{
                      background: 'transparent',
                      border: 'none',
                      cursor: 'pointer',
                      fontSize: '1.2rem',
                      opacity: 0.6,
                      transition: 'opacity 0.3s'
                    }}
                    onMouseEnter={(e) => e.target.style.opacity = '1'}
                    onMouseLeave={(e) => e.target.style.opacity = '0.6'}
                  >
                    ❌
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

function getNotificationColor(type) {
  const colors = {
    'sentier_danger': '#e74c3c',
    'camping_plein': '#f39c12',
    'ressource_menacee': '#27ae60',
    'reservation_confirmee': '#3498db',
    'meteo_alerte': '#9b59b6'
  };
  return colors[type] || '#34495e';
}
