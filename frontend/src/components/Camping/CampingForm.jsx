import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

export default function CampingForm({ mode }) {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({ nom: '', localisation: '', capacite: '' });

  useEffect(() => {
    if (mode === 'edit' && id) {
      fetch(`http://localhost:8080/api/camping.php?id=${id}`)
        .then(res => res.json())
        .then(data => setForm(data));
    }
  }, [mode, id]);

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = e => {
    e.preventDefault();
    const method = mode === 'edit' ? 'PUT' : 'POST';
    fetch(`http://localhost:8080/api/camping.php${mode === 'edit' ? `?id=${id}` : ''}`, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form)
    })
      .then(res => res.json())
      .then(() => navigate('/campings'));
  };

  return (
    <form onSubmit={handleSubmit}>
      <label>Nom : <input name="nom" value={form.nom} onChange={handleChange} required /></label><br />
      <label>Localisation : <input name="localisation" value={form.localisation} onChange={handleChange} required /></label><br />
      <label>Capacité : <input name="capacite" value={form.capacite} onChange={handleChange} required type="number" /></label><br />
      <button type="submit">{mode === 'edit' ? 'Mettre à jour' : 'Créer'}</button>
    </form>
  );
}
