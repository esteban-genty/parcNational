import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

function CampingDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [camping, setCamping] = useState(null);
  const [isAdmin, setIsAdmin] = useState(false);

  useEffect(() => {
    fetch(`/parcNational/backend/api/public/camping.php?id=${id}`)
      .then(res => res.json())
      .then(data => setCamping(data));
    // Vérifie le rôle admin (exemple: token dans localStorage)
    const token = localStorage.getItem('access_token');
    if (token) {
      // Optionnel: vérifier le rôle via un endpoint ou payload JWT
      setIsAdmin(true);
    }
  }, [id]);

  const handleDelete = () => {
    const token = localStorage.getItem('access_token');
    if (!token) return alert('Token admin manquant');
    if (!window.confirm('Supprimer ce camping ?')) return;
    fetch(`/parcNational/backend/api/admin/camping.php?id=${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
      .then(res => res.json())
      .then(() => navigate('/campings'));
  };

  if (!camping) return <div>Chargement...</div>;

  return (
    <div>
      <h3>{camping.nom}</h3>
      <p>Localisation : {camping.localisation}</p>
      <p>Capacité : {camping.capacite}</p>
      {isAdmin && (
        <button style={{color:'white',background:'red'}} onClick={handleDelete}>Supprimer (admin)</button>
      )}
    </div>
  );
}

export default CampingDetail;
