import { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, useNavigate, Navigate } from "react-router-dom";

// Components
import AnimatedHeader from "./components/AnimatedHeader";

// Pages
import Home from "./pages/HomePageNew";
import RegisterPage from "./pages/auth/RegisterPage";
import LoginPage from "./pages/auth/LoginPage";
import DashboardPage from "./pages/visiteur/DashboardPage";
import CampingDetailPage from "./pages/admin/CampingDetailPage";
import CampingCreatePage from "./pages/admin/CampingCreatePage";
import CampingEditPage from "./pages/admin/CampingEditPage";

// CSS Global
import './css/animations.css';

function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    // Vérification session au chargement (API moderne avec JWT)
    const checkSession = async () => {
      try {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
          setLoading(false);
          return;
        }
        
        const response = await fetch(
          "http://localhost:8080/api/auth/profile.php",
          {
            method: "GET",
            credentials: "include",
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            }
          }
        );
        
        const data = await response.json();
        console.log("✅ Session check - Réponse API:", data);
        console.log("📦 data.success:", data.success);
        console.log("👤 data.data:", data.data);
        
        // L'API profile retourne { success: true, data: { id, nom, email, role } }
        if (data.success && data.data) {
          console.log("✅ Utilisateur trouvé, setUser appelé avec:", data.data);
          setUser(data.data);
        } else if (data.id && data.email) {
          // Fallback si l'API retourne directement les données
          console.log("✅ Fallback - Utilisateur trouvé:", data);
          setUser(data);
        } else {
          // Token invalide ou expiré
          console.log("❌ Token invalide, nettoyage localStorage");
          localStorage.removeItem('access_token');
          localStorage.removeItem('refresh_token');
        }
      } catch (error) {
        console.error("Erreur session :", error);
        // Si le token est invalide, le supprimer
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
      } finally {
        setLoading(false);
      }
    };

    checkSession();
  }, []);

  const handleLogout = async () => {
    try {
      const token = localStorage.getItem('access_token');
      
      await fetch(
        "http://localhost:8080/api/auth/logout.php",
        {
          method: "POST",
          credentials: "include",
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        }
      );
      
      // Supprimer les tokens
      localStorage.removeItem('access_token');
      localStorage.removeItem('refresh_token');
      
      setUser(null);
      navigate("/");
    } catch (error) {
      console.error("Erreur logout:", error);
    }
  };

  if (loading) {
    return <div style={{ padding: '50px', textAlign: 'center' }}>Chargement...</div>;
  }

  return (
    <>
      <AnimatedHeader user={user} onLogout={handleLogout} />
      
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/register" element={<RegisterPage setUser={setUser} />} />
        <Route path="/login" element={user ? <Navigate to="/dashboard" replace /> : <LoginPage setUser={setUser} />} />
        <Route path="/dashboard/*" element={user ? <DashboardPage user={user} /> : <Navigate to="/login" replace />} />
        
        {/* Routes Campings */}
        <Route path="/camping/:id" element={<CampingDetailPage />} />
        <Route path="/camping/create" element={user?.role === 'admin' ? <CampingCreatePage /> : <Navigate to="/login" replace />} />
        <Route path="/camping/edit/:id" element={user?.role === 'admin' ? <CampingEditPage /> : <Navigate to="/login" replace />} />
      </Routes>
    </>
  );
}

export default function AppWrapper() {
  return (
    <Router>
      <App />
    </Router>
  );
}

