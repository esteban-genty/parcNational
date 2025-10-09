import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

export default function CampingList() {
  const [campings, setCampings] = useState([]);

  useEffect(() => {
    fetch('http://localhost:8080/api/camping.php')
      .then(res => res.json())
      .then(data => setCampings(data));
  }, []);

  return (
    <ul>
      {campings.map(camping => (
        <li key={camping.id}>
          <Link to={`/campings/${camping.id}`}>{camping.nom}</Link>
        </li>
      ))}
    </ul>
  );
}
