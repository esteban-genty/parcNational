import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import '../../css/SentiersMap.css';
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

  const getDifficultyClass = (difficulte) => {
    const diff = difficulte?.toLowerCase();
    if (diff === 'facile' || diff === 'moyen' || diff === 'difficile') {
      return diff;
    }
    return 'default';
  };

  return (
    <MapContainer 
      center={center} 
      zoom={12} 
      className="sentiers-map-container"
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      
      {sentiers.map((sentier, index) => {
        // Utiliser les coordonnées GPS de la BDD, ou fallback sur le centre
        const position = sentier.latitude && sentier.longitude 
          ? [parseFloat(sentier.latitude), parseFloat(sentier.longitude)]
          : center;
        
        const difficultyClass = getDifficultyClass(sentier.difficulte);
        
        return (
          <Marker 
            key={sentier.id} 
            position={position}
            icon={L.divIcon({
              className: 'custom-marker',
              html: `<div class="sentier-marker ${difficultyClass}"></div>`,
              iconSize: [30, 30],
              iconAnchor: [15, 15]
            })}
          >
            <Popup>
              <div className="sentier-popup">
                <h4 className="sentier-popup-title">
                  {sentier.nom}
                </h4>
                <p className="sentier-popup-info">
                  <strong>Difficulté:</strong> 
                  <span className={`sentier-difficulty-badge ${difficultyClass}`}>
                    {sentier.difficulte}
                  </span>
                </p>
                {sentier.description && (
                  <p className="sentier-popup-description">
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
