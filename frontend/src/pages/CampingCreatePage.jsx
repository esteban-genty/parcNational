// Page création camping - formulaire de création via CampingForm
import React from 'react';
import CampingForm from '../components/Camping/CampingForm';

export default function CampingCreatePage() {
  return (
    <div>
      <h2>Créer un camping</h2>
      <CampingForm mode="create" />
    </div>
  );
}
