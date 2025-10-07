import { useState, useEffect } from 'react';
import { useCamping, useSentier, useNotifications } from '../../hooks/useAPI';
import DashboardNotificationsNew from './DashboardNotificationsNew';
import MeteoWidget from './MeteoWidget';
import '../../css/animations.css';

export default function AdminDashboard({ user }) {
  const [stats, setStats] = useState({ campings: 0, sentiers: 0, notifications: 0 });
  const { getAll: getCampings } = useCamping();
  const { getAll: getSentiers } = useSentier();
  const { getActive } = useNotifications();

  useEffect(() => {
    loadStats();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const loadStats = async () => {
    try {
      const [campings, sentiers, notifs] = await Promise.all([
        getCampings(),
        getSentiers(),
        getActive()
      ]);

      setStats({
        campings: Array.isArray(campings) ? campings.length : 0,
        sentiers: Array.isArray(sentiers) ? sentiers.length : 0,
        notifications: Array.isArray(notifs) ? notifs.length : 0
      });
    } catch (error) {
      console.error('Erreur chargement stats:', error);
    }
  };

  return (
    <div className="page-content fade-in">
      <div style={{ marginBottom: '2rem' }}>
        <h2 className="text-gradient">⚙️ Administration du Parc</h2>
        <p style={{ fontSize: '1.1rem', opacity: 0.8 }}>
          Bienvenue <strong>{user.nom}</strong>, gérez le parc national
        </p>
      </div>

      {/* Stats Cards + Météo */}
      <div className="grid grid-4" style={{ marginBottom: '3rem' }}>
        <div className="card" style={{ background: 'linear-gradient(135deg, #3498db, #2980b9)', color: 'white' }}>
          <h3 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>{stats.campings}</h3>
          <p>🏕️ Campings</p>
        </div>
        <div className="card" style={{ background: 'linear-gradient(135deg, #27ae60, #229954)', color: 'white' }}>
          <h3 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>{stats.sentiers}</h3>
          <p>🥾 Sentiers</p>
        </div>
        <div className="card" style={{ background: 'linear-gradient(135deg, #e74c3c, #c0392b)', color: 'white' }}>
          <h3 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>{stats.notifications}</h3>
          <p>🔔 Notifications</p>
        </div>
        
        {/* Météo Widget */}
        <MeteoWidget />
      </div>

      {/* Notifications */}
      <DashboardNotificationsNew isAdmin={true} />
    </div>
  );
}