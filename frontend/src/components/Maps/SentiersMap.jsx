import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

// Fix Leaflet default marker icons
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

export default function SentiersMap({ sentiers }) {
  const center = [43.2095, 5.4378];

  const getSentierPosition = (index) => {
    const positions = [
      [43.22, 5.44], [43.21, 5.43], [43.20, 5.45], [43.23, 5.44],
      [43.19, 5.42], [43.24, 5.46], [43.18, 5.44], [43.25, 5.43],
      [43.21, 5.47], [43.22, 5.41]
    ];
    return positions[index % positions.length];
  };

  const getDifficultyColor = (difficulte) => {
    switch(difficulte?.toLowerCase()) {
      case 'facile': return '#27ae60';
      case 'moyen': return '#f39c12';
      case 'difficile': return '#e74c3c';
      default: return '#95a5a6';
    }
  };

  return (
    <MapContainer 
      center={center} 
      zoom={12} 
      style={{ height: '600px', width: '100%' }}
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      
      {sentiers.map((sentier, index) => {
        const position = getSentierPosition(index);
        return (
          <Marker 
            key={sentier.id} 
            position={position}
            icon={L.divIcon({
              className: 'custom-marker',
              html: `<div style="background-color: ${getDifficultyColor(sentier.difficulte)}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
              iconSize: [30, 30],
              iconAnchor: [15, 15]
            })}
          >
            <Popup>
              <div style={{ minWidth: '250px' }}>
                <h4 style={{ marginBottom: '0.5rem', color: '#2c3e50' }}>
                  {sentier.nom}
                </h4>
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
                  <p style={{ margin: '0.5rem 0 0 0', fontSize: '0.85rem', opacity: 0.8 }}>
                    {sentier.description}
                  </p>
                )}
              </div>
            </Popup>
          </Marker>
        );
      })}
    </MapContainer>
  );
}
