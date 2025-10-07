import { Link, Routes, Route } from "react-router-dom";
import '../css/dashboard-new.css';
import '../css/animations.css';

// Components
import DashboardNotifications from "../components/Dashboard/DashboardNotificationsNew";
import AdminDashboard from "../components/Dashboard/AdminDashboard";
import VisiteurDashboard from "../components/Dashboard/VisiteurDashboard";

export default function Dashboard({ user }) {
  if (!user) {
    return (
      <div className="container-fluid" style={{ padding: '4rem 2rem', textAlign: 'center' }}>
        <div className="card">
          <h2>⚠️ Accès non autorisé</h2>
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
          <h3>🏞️ Dashboard</h3>
          <p className="user-role">{user.role === 'admin' ? '⚙️ Administrateur' : '👤 Visiteur'}</p>
        </div>

        <nav className="sidebar-nav">
          {user.role === 'admin' ? (
            <>
              <h5>Administration</h5>
              <Link to="/dashboard" className="nav-link">
                📊 Vue d'ensemble
              </Link>
              <Link to="/dashboard/comptes" className="nav-link">
                👥 Gestion des comptes
              </Link>
              <Link to="/dashboard/camping" className="nav-link">
                🏕️ Gestion des campings
              </Link>
              <Link to="/dashboard/sentiers" className="nav-link">
                🥾 Gestion des sentiers
              </Link>
              <Link to="/dashboard/notifications" className="nav-link">
                🔔 Notifications système
              </Link>
            </>
          ) : (
            <>
              <h5>Mon Espace</h5>
              <Link to="/dashboard" className="nav-link">
                🏠 Accueil
              </Link>
              <Link to="/dashboard/sentiers" className="nav-link">
                🗺️ Carte des sentiers
              </Link>
              <Link to="/dashboard/camping" className="nav-link">
                🏕️ Réserver un camping
              </Link>
              <Link to="/dashboard/ressources" className="nav-link">
                🌿 Ressources naturelles
              </Link>
              <Link to="/dashboard/mes-reservations" className="nav-link">
                📅 Mes réservations
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
          <Route path="/camping" element={<CampingPage />} />
          <Route path="/ressources" element={<RessourcesPage />} />
          {user.role === 'admin' && <Route path="/comptes" element={<ComptesPage />} />}
          {user.role === 'admin' && <Route path="/notifications" element={<NotificationsAdminPage />} />}
        </Routes>
      </main>
    </div>
  );
}

// Pages placeholder (à créer séparément)
function SentiersPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🗺️ Carte des Sentiers</h2>
      <div className="card">
        <div id="map-sentiers" style={{ height: '600px', borderRadius: '12px' }}>
          {/* Leaflet Map ici */}
          <p style={{ padding: '2rem', textAlign: 'center' }}>Carte Leaflet des sentiers à intégrer</p>
        </div>
      </div>
    </div>
  );
}

function CampingPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🏕️ Campings Disponibles</h2>
      <div className="card">
        <div id="map-camping" style={{ height: '600px', borderRadius: '12px' }}>
          {/* Leaflet Map ici */}
          <p style={{ padding: '2rem', textAlign: 'center' }}>Carte Leaflet des campings à intégrer</p>
        </div>
      </div>
    </div>
  );
}

function RessourcesPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🌿 Ressources Naturelles</h2>
      <div className="card">
        <div id="map-ressources" style={{ height: '600px', borderRadius: '12px' }}>
          {/* Leaflet Map ici */}
          <p style={{ padding: '2rem', textAlign: 'center' }}>Carte Leaflet des ressources à intégrer</p>
        </div>
      </div>
    </div>
  );
}

function ComptesPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">👥 Gestion des Comptes</h2>
      <div className="card">
        <p>Liste des utilisateurs et gestion admin</p>
      </div>
    </div>
  );
}

function NotificationsAdminPage() {
  return (
    <div className="page-content">
      <h2 className="text-gradient">🔔 Notifications Système</h2>
      <DashboardNotifications isAdmin={true} />
    </div>
  );
}
