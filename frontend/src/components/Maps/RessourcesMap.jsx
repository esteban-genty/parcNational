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

export default function RessourcesMap({ ressources }) {
  const center = [43.2095, 5.4378]; // Centre du Parc des Calanques

  const getRessourcePosition = (index) => {
    const offsets = [
      [0, 0], [0.01, 0.01], [-0.01, 0.01], [0.01, -0.01], [-0.01, -0.01],
      [0.02, 0], [-0.02, 0], [0, 0.02], [0, -0.02],
      [0.015, 0.015], [-0.015, 0.015], [0.015, -0.015], [-0.015, -0.015],
      [0.025, 0.01], [-0.025, 0.01], [0.01, 0.025], [0.01, -0.025],
      [0.03, 0], [-0.03, 0], [0, 0.03], [0, -0.03],
      [0.02, 0.02], [-0.02, 0.02], [0.02, -0.02], [-0.02, -0.02]
    ];
    const offset = offsets[index % offsets.length];
    return [center[0] + offset[0], center[1] + offset[1]];
  };

  const getMarkerIcon = (type) => {
    const colors = {
      'Flore': '#27ae60',
      'Faune': '#e67e22',
      'Marine': '#3498db',
      'Géologie': '#95a5a6'
    };
    
    return L.divIcon({
      className: 'custom-marker',
      html: `<div style="background-color: ${colors[type] || '#95a5a6'}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
      iconSize: [30, 30],
      iconAnchor: [15, 15]
    });
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
      
      {ressources.map((ressource, index) => {
        const position = getRessourcePosition(index);
        return (
          <Marker 
            key={ressource.id} 
            position={position}
            icon={getMarkerIcon(ressource.type)}
          >
            <Popup>
              <div style={{ minWidth: '200px' }}>
                <h4 style={{ marginBottom: '0.5rem', color: '#2c3e50' }}>
                  {ressource.nom}
                </h4>
                <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                  <strong>Type:</strong> {ressource.type}
                </p>
                <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                  <strong>État:</strong> 
                  <span style={{ 
                    marginLeft: '0.5rem',
                    padding: '2px 8px',
                    borderRadius: '4px',
                    backgroundColor: ressource.etat === 'Bon' ? '#d4edda' : 
                                   ressource.etat === 'Excellent' ? '#c3e6cb' :
                                   ressource.etat?.includes('danger') ? '#f8d7da' : '#fff3cd',
                    color: '#000',
                    fontSize: '0.85rem'
                  }}>
                    {ressource.etat}
                  </span>
                </p>
              </div>
            </Popup>
          </Marker>
        );
      })}
    </MapContainer>
  );
}
