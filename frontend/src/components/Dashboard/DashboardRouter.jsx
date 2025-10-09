import { Routes, Route } from "react-router-dom";

// Pages visiteur
import SentiersPage from "../../pages/visiteur/SentiersPage";
import CampingsPage from "../../pages/visiteur/CampingsPage";
import RessourcesPage from "../../pages/visiteur/RessourcesNaturellesPage";

// Pages admin
import CampingsAdminPage from "../../pages/admin/CampingsAdminPage";

// Page compte
import AccountPage from "../../pages/auth/AccountPage";

/**
 * DashboardRouter - Gère les routes INTERNES du dashboard
 * 
 * Routes disponibles :
 * - /dashboard/sentiers       → Liste des sentiers (public)
 * - /dashboard/camping        → Liste des campings (public)
 * - /dashboard/ressources     → Liste des ressources naturelles (public)
 * - /dashboard/admin/campings → Gestion admin des campings (admin uniquement)
 * - /dashboard/comptes        → Gestion des comptes (admin uniquement)
 */
export default function DashboardRouter({ userRole }) {
  return (
    <Routes>
      {/* Routes visiteur - Accessibles à tous */}
      <Route path="sentiers" element={<SentiersPage />} />
      <Route path="camping" element={<CampingsPage />} />
      <Route path="ressources" element={<RessourcesPage />} />
      
      {/* Routes admin - Accessibles uniquement aux admins */}
      {userRole === 'admin' && (
        <>
          <Route path="admin/campings" element={<CampingsAdminPage />} />
          <Route path="comptes" element={<AccountPage />} />
        </>
      )}
    </Routes>
  );
}
