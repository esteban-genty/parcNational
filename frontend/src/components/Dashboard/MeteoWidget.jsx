import { useState, useEffect } from 'react';

/**
 * 🌤️ Widget Météo pour les Calanques de Marseille
 * Utilise l'API Open-Meteo (gratuite, pas de clé API nécessaire)
 */
export default function MeteoWidget() {
  const [meteo, setMeteo] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Coordonnées des Calanques de Marseille
  const latitude = 43.2094;
  const longitude = 5.4371;

  useEffect(() => {
    fetchMeteo();
    // Rafraîchir toutes les 30 minutes
    const interval = setInterval(fetchMeteo, 30 * 60 * 1000);
    return () => clearInterval(interval);
  }, []);

  const fetchMeteo = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code&daily=temperature_2m_max,temperature_2m_min,weather_code&timezone=Europe/Paris&forecast_days=3`
      );
      const data = await response.json();
      setMeteo(data);
      setError(null);
    } catch (err) {
      setError("Impossible de charger la météo");
      console.error("Erreur météo:", err);
    } finally {
      setLoading(false);
    }
  };

  const getWeatherIcon = (code) => {
    // Codes WMO Weather interpretation
    if (code === 0) return '☀️';
    if (code <= 3) return '⛅';
    if (code <= 48) return '🌫️';
    if (code <= 67) return '🌧️';
    if (code <= 77) return '🌨️';
    if (code <= 82) return '🌧️';
    if (code <= 86) return '🌨️';
    if (code <= 99) return '⛈️';
    return '🌤️';
  };

  const getWeatherText = (code) => {
    if (code === 0) return 'Ciel dégagé';
    if (code <= 3) return 'Partiellement nuageux';
    if (code <= 48) return 'Brouillard';
    if (code <= 67) return 'Pluie';
    if (code <= 77) return 'Neige';
    if (code <= 82) return 'Averses';
    if (code <= 86) return 'Averses de neige';
    if (code <= 99) return 'Orage';
    return 'Variable';
  };

  if (loading) {
    return (
      <div className="card" style={{ 
        background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        color: 'white',
        padding: '1.5rem'
      }}>
        <h3 style={{ marginBottom: '1rem' }}>🌤️ Météo Calanques</h3>
        <p>Chargement...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="card" style={{ 
        background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        color: 'white',
        padding: '1.5rem'
      }}>
        <h3 style={{ marginBottom: '1rem' }}>🌤️ Météo Calanques</h3>
        <p>⚠️ {error}</p>
      </div>
    );
  }

  if (!meteo) return null;

  const current = meteo.current;
  const daily = meteo.daily;

  return (
    <div className="card" style={{ 
      background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
      color: 'white',
      padding: '1.5rem'
    }}>
      <h3 style={{ marginBottom: '1rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
        🌤️ Météo Calanques
      </h3>

      {/* Météo actuelle */}
      <div style={{ 
        background: 'rgba(255, 255, 255, 0.1)', 
        padding: '1rem', 
        borderRadius: '8px',
        marginBottom: '1rem'
      }}>
        <div style={{ 
          display: 'flex', 
          alignItems: 'center', 
          justifyContent: 'space-between',
          marginBottom: '0.5rem'
        }}>
          <div style={{ fontSize: '3rem' }}>
            {getWeatherIcon(current.weather_code)}
          </div>
          <div style={{ textAlign: 'right' }}>
            <div style={{ fontSize: '2.5rem', fontWeight: 'bold' }}>
              {Math.round(current.temperature_2m)}°C
            </div>
            <div style={{ fontSize: '0.9rem', opacity: 0.9 }}>
              {getWeatherText(current.weather_code)}
            </div>
          </div>
        </div>
        
        <div style={{ 
          display: 'grid', 
          gridTemplateColumns: '1fr 1fr', 
          gap: '0.5rem',
          fontSize: '0.85rem',
          marginTop: '0.75rem'
        }}>
          <div>💧 Humidité: {current.relative_humidity_2m}%</div>
          <div>💨 Vent: {Math.round(current.wind_speed_10m)} km/h</div>
        </div>
      </div>

      {/* Prévisions 3 jours */}
      <div style={{ fontSize: '0.85rem' }}>
        <div style={{ fontWeight: 'bold', marginBottom: '0.5rem', opacity: 0.9 }}>
          📅 Prévisions 3 jours
        </div>
        <div style={{ display: 'grid', gap: '0.5rem' }}>
          {daily.time.slice(0, 3).map((date, index) => (
            <div key={date} style={{ 
              display: 'flex', 
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'rgba(255, 255, 255, 0.1)',
              padding: '0.5rem',
              borderRadius: '6px'
            }}>
              <span>
                {index === 0 ? "Aujourd'hui" : 
                 index === 1 ? "Demain" : 
                 new Date(date).toLocaleDateString('fr-FR', { weekday: 'short' })}
              </span>
              <span style={{ fontSize: '1.2rem' }}>
                {getWeatherIcon(daily.weather_code[index])}
              </span>
              <span style={{ fontWeight: 'bold' }}>
                {Math.round(daily.temperature_2m_min[index])}° / {Math.round(daily.temperature_2m_max[index])}°
              </span>
            </div>
          ))}
        </div>
      </div>

      <div style={{ 
        fontSize: '0.75rem', 
        opacity: 0.7, 
        marginTop: '1rem',
        textAlign: 'center'
      }}>
        📍 Marseille - Calanques
      </div>
    </div>
  );
}
