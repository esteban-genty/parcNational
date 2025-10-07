import { Link, Routes, Route } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../utils/icons';
import '../css/dashboard-new.css';
import '../css/animations.css';

// Components
import AdminDashboard from "../components/Dashboard/AdminDashboard";
import VisiteurDashboard from "../components/Dashboard/VisiteurDashboard";

// Pages
import SentiersPage from "./SentiersPage";
import CampingsPage from "./CampingsPage";
import RessourcesNaturellesPage from "./RessourcesNaturellesPage";

export default function Dashboard({ user }) {
  if (!user) {
    return (
      <div className="container-fluid" style={{ padding: '4rem 2rem', textAlign: 'center' }}>
        <div className="card">
          <h2><FontAwesomeIcon icon={icons.warning} /> Accès non autorisé</h2>
          <p>Vous devez être connecté pour accéder au dashboard.</p>
          <Link to="/login" className="btn btn-primary">Se connecter</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="dashboard-container">
      {/* Sidebar Navigation */}
      <aside className="dashboard-sidebar">
        <div className="sidebar-header">
          <h3><FontAwesomeIcon icon={icons.mountain} /> Dashboard</h3>
          <p className="user-role">
            {user.role === 'admin' ? (
              <><FontAwesomeIcon icon={icons.cog} /> Administrateur</>
            ) : (
              <><FontAwesomeIcon icon={icons.user} /> Visiteur</>
            )}
          </p>
        </div>

        <nav className="sidebar-nav">
          {user.role === 'admin' ? (
            <>
              <h5>Administration</h5>
              <Link to="/dashboard" className="nav-link">
                <FontAwesomeIcon icon={icons.chart} /> Vue d'ensemble
              </Link>
              <Link to="/dashboard/camping" className="nav-link">
                <FontAwesomeIcon icon={icons.camping} /> Gestion des campings
              </Link>
              <Link to="/dashboard/sentiers" className="nav-link">
                <FontAwesomeIcon icon={icons.hiking} /> Gestion des sentiers
              </Link>
            </>
          ) : (
            <>
              <h5>Mon Espace</h5>
              <Link to="/dashboard" className="nav-link">
                <FontAwesomeIcon icon={icons.home} /> Accueil
              </Link>
              <Link to="/dashboard/sentiers" className="nav-link">
                <FontAwesomeIcon icon={icons.map} /> Carte des sentiers
              </Link>
              <Link to="/dashboard/camping" className="nav-link">
                <FontAwesomeIcon icon={icons.camping} /> Réserver un camping
              </Link>
              <Link to="/dashboard/ressources" className="nav-link">
                <FontAwesomeIcon icon={icons.leaf} /> Ressources naturelles
              </Link>
            </>
          )}
        </nav>

        <div className="sidebar-footer">
          <div className="user-info-card">
            <p><strong>{user.nom}</strong></p>
            <p style={{ fontSize: '0.9rem', opacity: 0.8 }}>{user.email}</p>
          </div>
        </div>
      </aside>

      {/* Main Content */}
      <main className="dashboard-main">
        <Routes>
          <Route 
            path="/" 
            element={
              user.role === 'admin' ? 
              <AdminDashboard user={user} /> : 
              <VisiteurDashboard user={user} />
            } 
          />
          <Route path="/sentiers" element={<SentiersPage />} />
          <Route path="/camping" element={<CampingsPage />} />
          <Route path="/ressources" element={<RessourcesNaturellesPage />} />
        </Routes>
      </main>
    </div>
  );
}
