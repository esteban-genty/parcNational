import { useState, useEffect } from 'react';

/**
 * 🔔 Composant d'affichage des notifications utilisateur
 * Récupère et affiche les notifications depuis l'API
 */
export default function DashboardNotifications({ userId }) {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // 📥 Récupération des notifications au chargement
  useEffect(() => {
    fetchNotifications();
  }, [userId]);

  // 🌐 Fonction pour récupérer les notifications depuis l'API
  const fetchNotifications = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `http://localhost/parcNational/backend/api/public/notifications.php?utilisateur_id=${userId}`,
        {
          method: 'GET',
          credentials: 'include', // Inclure les cookies de session
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      if (!response.ok) {
        throw new Error('Erreur lors de la récupération des notifications');
      }

      const data = await response.json();
      
      // Vérifier si data est un tableau
      if (Array.isArray(data)) {
        setNotifications(data);
      } else {
        setNotifications([]);
      }
      
      setError(null);
    } catch (err) {
      console.error('Erreur notifications:', err);
      setError(err.message);
      setNotifications([]);
    } finally {
      setLoading(false);
    }
  };

  // 📍 Affichage conditionnel
  if (loading) {
    return (
      <div className="notifications-container">
        <h3>🔔 Notifications</h3>
        <p className="loading">Chargement des notifications...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="notifications-container">
        <h3>🔔 Notifications</h3>
        <p className="error">❌ {error}</p>
      </div>
    );
  }

  if (notifications.length === 0) {
    return (
      <div className="notifications-container">
        <h3>🔔 Notifications</h3>
        <p className="no-notifications">✅ Aucune nouvelle notification</p>
      </div>
    );
  }

  return (
    <div className="notifications-container">
      <h3>🔔 Notifications ({notifications.length})</h3>
      <div className="notifications-list">
        {notifications.map((notif) => (
          <div 
            key={notif.id} 
            className={`notification-item ${notif.lu ? 'read' : 'unread'}`}
          >
            <div className="notification-content">
              <p className="notification-message">{notif.message}</p>
              <span className="notification-date">
                {new Date(notif.created_at).toLocaleDateString('fr-FR', {
                  day: '2-digit',
                  month: 'short',
                  year: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
                })}
              </span>
            </div>
            {!notif.lu && <span className="notification-badge">●</span>}
          </div>
        ))}
      </div>
    </div>
  );
}
