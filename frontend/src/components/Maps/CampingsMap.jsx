import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import '../../css/CampingsMap.css';
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

  return (
    <MapContainer 
      center={center} 
      zoom={12} 
      className="campings-map-container"
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      
      {campings.map((camping, index) => {
        // Utiliser les coordonnées GPS de la BDD, ou fallback sur le centre
        const position = camping.latitude && camping.longitude 
          ? [parseFloat(camping.latitude), parseFloat(camping.longitude)]
          : center;
        
        return (
          <Marker 
            key={camping.id} 
            position={position}
            icon={L.divIcon({
              className: 'custom-marker',
              html: `<div class="camping-marker"></div>`,
              iconSize: [35, 35],
              iconAnchor: [17, 17]
            })}
          >
            <Popup>
              <div className="camping-popup">
                <h4 className="camping-popup-title">
                  {camping.nom}
                </h4>
                <p className="camping-popup-info">
                  <strong>Localisation:</strong> {camping.localisation}
                </p>
                <p className="camping-popup-info">
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
