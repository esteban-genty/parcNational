import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../../utils/icons';

// Fix Leaflet default marker icons
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

export default function CampingsMap({ campings }) {
  const center = [43.2095, 5.4378];

  const getCampingPosition = (index) => {
    const positions = [
      [43.2095, 5.4378], [43.218, 5.428], [43.204, 5.449], [43.213, 5.455],
      [43.225, 5.438], [43.197, 5.441], [43.215, 5.421]
    ];
    return positions[index % positions.length];
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
      
      {campings.map((camping, index) => {
        const position = getCampingPosition(index);
        return (
          <Marker 
            key={camping.id} 
            position={position}
            icon={L.divIcon({
              className: 'custom-marker',
              html: `<div style="background-color: #27ae60; width: 35px; height: 35px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
              iconSize: [35, 35],
              iconAnchor: [17, 17]
            })}
          >
            <Popup>
              <div style={{ minWidth: '250px' }}>
                <h4 style={{ marginBottom: '0.5rem', color: '#2c3e50' }}>
                  {camping.nom}
                </h4>
                <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                  <strong>Localisation:</strong> {camping.localisation}
                </p>
                <p style={{ margin: '0.25rem 0', fontSize: '0.9rem' }}>
                  <strong>Capacité:</strong> {camping.capacite} places
                </p>
              </div>
            </Popup>
          </Marker>
        );
      })}
    </MapContainer>
  );
}
