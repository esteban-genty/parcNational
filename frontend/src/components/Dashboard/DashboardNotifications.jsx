import { useState, useEffect } from 'react';
import '../../css/notifications.css';

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

      // 🔍 Vérifier d'abord si la réponse est OK
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }

      // 📝 Récupérer le texte brut pour debug
      const textData = await response.text();
      
      // 🛡️ Vérifier que la réponse n'est pas vide
      if (!textData || textData.trim() === '') {
        console.warn('Réponse vide de l\'API');
        setNotifications([]);
        setError(null);
        return;
      }

      // 🔄 Parser le JSON
      let data;
      try {
        data = JSON.parse(textData);
      } catch (parseError) {
        console.error('Erreur de parsing JSON:', parseError);
        console.error('Réponse reçue:', textData);
        throw new Error('Format de réponse invalide (pas du JSON)');
      }

      // ✅ Vérifier si c'est une erreur du backend
      if (data.error) {
        throw new Error(data.message || 'Erreur serveur');
      }
      
      // 📦 Vérifier si data est un tableau
      if (Array.isArray(data)) {
        setNotifications(data);
      } else if (data.data && Array.isArray(data.data)) {
        setNotifications(data.data);
      } else {
        console.warn('Format de données inattendu:', data);
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
            className="notification-item unread"
          >
            <div className="notification-content">
              <p className="notification-title"><strong>{notif.titre}</strong></p>
              <p className="notification-message">{notif.message}</p>
              <span className="notification-date">
                {new Date(notif.date_envoi).toLocaleDateString('fr-FR', {
                  day: '2-digit',
                  month: 'long',
                  year: 'numeric'
                })}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
