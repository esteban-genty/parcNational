// Page liste des campings - affiche la liste via le composant CampingList
import React from 'react';
import CampingList from '../components/Camping/CampingList';

export default function CampingListPage() {
  return (
    <div>
      <h2>Liste des campings</h2>
      <CampingList />
    </div>
  );
}
