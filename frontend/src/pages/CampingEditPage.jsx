// Page édition camping - formulaire d'édition via CampingForm
import React from 'react';
import CampingForm from '../components/Camping/CampingForm';

export default function CampingEditPage() {
  return (
    <div>
      <h2>Éditer un camping</h2>
      <CampingForm mode="edit" />
    </div>
  );
}
