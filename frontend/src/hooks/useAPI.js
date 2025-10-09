/**
 * Hook personnalisé pour les appels API
 * Principe DRY - Ne pas se répéter
 */
import { useState, useCallback } from 'react';

const API_BASE = 'http://localhost:8080';

export function useAPI() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const request = useCallback(async (endpoint, options = {}) => {
    setLoading(true);
    setError(null);

    try {
      // Récupérer le token JWT depuis localStorage
      const token = localStorage.getItem('access_token');
      
      const response = await fetch(`${API_BASE}${endpoint}`, {
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          // Ajouter le token JWT si disponible
          ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
          ...options.headers,
        },
        ...options,
      });

      const data = await response.json();

      if (!response.ok || data.success === false) {
        throw new Error(data.error || 'Une erreur est survenue');
      }

      setLoading(false);
      return data;
    } catch (err) {
      setError(err.message);
      setLoading(false);
      throw err;
    }
  }, []);

  const get = useCallback((endpoint) => {
    return request(endpoint, { method: 'GET' });
  }, [request]);

  const post = useCallback((endpoint, body) => {
    return request(endpoint, {
      method: 'POST',
      body: JSON.stringify(body),
    });
  }, [request]);

  const put = useCallback((endpoint, body) => {
    return request(endpoint, {
      method: 'PUT',
      body: JSON.stringify(body),
    });
  }, [request]);

  const del = useCallback((endpoint, body) => {
    return request(endpoint, {
      method: 'DELETE',
      body: JSON.stringify(body),
    });
  }, [request]);

  return { loading, error, get, post, put, del };
}

/**
 * Hook pour l'authentification (API moderne avec JWT)
 */
export function useAuth() {
  const { post, get } = useAPI();

  const login = useCallback(async (email, mot_de_passe) => {
    // Utilise la nouvelle API JWT
    const response = await post('/api/auth/login.php', {
      email,
      mot_de_passe,
    });
    
    // Stocker le token JWT dans localStorage
    if (response.tokens?.access_token) {
      localStorage.setItem('access_token', response.tokens.access_token);
      localStorage.setItem('refresh_token', response.tokens.refresh_token);
    }
    
    return response;
  }, [post]);

  const register = useCallback(async (nom, email, mot_de_passe) => {
    // Utilise la nouvelle API JWT
    const response = await post('/api/auth/register.php', {
      nom,
      email,
      mot_de_passe,
    });
    
    // Stocker le token JWT dans localStorage
    if (response.tokens?.access_token) {
      localStorage.setItem('access_token', response.tokens.access_token);
      localStorage.setItem('refresh_token', response.tokens.refresh_token);
    }
    
    return response;
  }, [post]);

  const logout = useCallback(async () => {
    // Supprimer les tokens
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    
    // Appeler l'API de déconnexion
    return await post('/api/auth/logout.php');
  }, [post]);

  const checkSession = useCallback(async () => {
    // Vérifier avec la nouvelle API profile
    return await get('/api/auth/profile.php');
  }, [get]);

  return { login, register, logout, checkSession };
}

/**
 * Hook pour les campings
 */
export function useCamping() {
  const { get, post, put, del } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/camping.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/camping.php?id=${id}`);
    return result.data || result;
  }, [get]);

  const create = useCallback(async (camping) => {
    const result = await post('/api/camping.php', camping);
    return result.data || result;
  }, [post]);

  const update = useCallback(async (camping) => {
    const result = await put('/api/camping.php', camping);
    return result.data || result;
  }, [put]);

  const remove = useCallback(async (id) => {
    const result = await del('/api/camping.php', { id });
    return result.data || result;
  }, [del]);

  return { getAll, getById, create, update, remove };
}

/**
 * Hook pour les sentiers
 */
export function useSentier() {
  const { get, post, put, del } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/public/sentiers.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/public/sentiers.php?id=${id}`);
    return result.data || result;
  }, [get]);

  return { getAll, getById };
}

/**
 * Hook pour les notifications RÉELLES
 */
export function useNotifications() {
  const { get, post, del } = useAPI();

  const getActive = useCallback(async (limit = 10) => {
    const result = await get(`/api/notifications.php?action=list&limit=${limit}`);
    return result.data || result;
  }, [get]);

  const generate = useCallback(async () => {
    const result = await get('/api/notifications.php?action=generate');
    return result.data || result;
  }, [get]);

  const remove = useCallback(async (id) => {
    const result = await del('/api/notifications.php?action=delete', { id });
    return result.data || result;
  }, [del]);

  return { getActive, generate, remove };
}

/**
 * Hook pour les ressources naturelles
 */
export function useRessources() {
  const { get } = useAPI();

  const getAll = useCallback(async () => {
    const result = await get('/api/public/ressources_naturelles.php');
    return result.data || result;
  }, [get]);

  const getById = useCallback(async (id) => {
    const result = await get(`/api/public/ressources_naturelles.php?id=${id}`);
    return result.data || result;
  }, [get]);

  return { getAll, getById };
}
