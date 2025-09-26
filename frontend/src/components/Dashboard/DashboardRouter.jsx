import { Routes, Route } from "react-router-dom";
import ProtectedRoute from "../ProtectedRoute";

// Pages internes
function Sentiers() { return <h2>Gestion des sentiers</h2>; }
function Camping() { return <h2>Réservations camping</h2>; }
function Ressources() { return <h2>Ressources naturelles</h2>; }

// Page importée
import Account from "../../pages/AccountPage";

export default function DashboardRouter({ userRole }) {
  return (
    <Routes>
      <Route path="sentiers" element={<Sentiers />} />
      <Route path="camping" element={<Camping />} />
      <Route path="ressources" element={<Ressources />} />
      <Route
        path="comptes"
        element={
          <ProtectedRoute userRole={userRole}>
            <Account />
          </ProtectedRoute>
        }
      />
    </Routes>
  );
}
